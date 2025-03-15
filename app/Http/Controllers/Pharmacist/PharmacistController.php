<?php

namespace App\Http\Controllers\Pharmacist;

use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ReturnOrder;
use App\Models\Inventory;
use App\Models\InventoryLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class PharmacistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('pharmacist');
    }
    
    // Dashboard
    public function index()
    {
        // Lấy tất cả đơn thuốc thay vì lọc theo trạng thái
        $pendingPrescriptions = Prescription::count();
        $lowStockItems = Medicine::where('stock_quantity', '<', 10)->count();
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        $pendingReturns = 0; // Tạm thời đặt giá trị là 0
        
        $recentPrescriptions = Prescription::with('patient', 'doctor')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('pharmacist.dashboard', compact(
            'pendingPrescriptions', 'lowStockItems', 'todayOrders', 'pendingReturns',
            'recentPrescriptions', 'recentOrders'
        ));
    }
    
    // Quản lý đơn thuốc
    public function pendingPrescriptions()
    {
        // Lấy tất cả đơn thuốc thay vì lọc theo trạng thái
        $prescriptions = Prescription::with('patient', 'doctor')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('pharmacist.prescriptions.pending', compact('prescriptions'));
    }
    
    public function prescriptionHistory()
    {
        // Lấy tất cả đơn thuốc thay vì lọc theo trạng thái
        $prescriptions = Prescription::with('patient', 'doctor')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('pharmacist.prescriptions.history', compact('prescriptions'));
    }
    
    public function showPrescription($id)
    {
        $prescription = Prescription::with(['patient', 'doctor', 'items.medicine'])
            ->findOrFail($id);
            
        return view('pharmacist.prescriptions.show', compact('prescription'));
    }
    
    public function processPrescription(Request $request, $id)
    {
        $prescription = Prescription::findOrFail($id);
        
        // Kiểm tra tồn kho
        foreach ($prescription->items as $item) {
            $medicine = Medicine::find($item->medicine_id);
            if ($medicine->stock_quantity < $item->quantity) {
                return redirect()->back()->with('error', "Thuốc {$medicine->name} không đủ số lượng trong kho!");
            }
        }
        
        DB::beginTransaction();
        
        try {
            // Cập nhật tồn kho
            foreach ($prescription->items as $item) {
                $medicine = Medicine::find($item->medicine_id);
                $medicine->stock_quantity -= $item->quantity;
                $medicine->save();
                
                // Ghi log
                InventoryLog::create([
                    'medicine_id' => $medicine->id,
                    'quantity' => -$item->quantity,
                    'type' => 'out',
                    'note' => "Xuất cho đơn thuốc #{$prescription->id}",
                    'user_id' => Auth::id()
                ]);
            }
            
            // Tạo đơn hàng
            $order = Order::create([
                'id_user' => $prescription->patient_id,
                'total_price' => $prescription->total_amount,
                'payment_method' => $request->payment_method,
                'status' => 'completed'
            ]);
            
            // Thêm chi tiết đơn hàng
            foreach ($prescription->items as $item) {
                OrderItem::create([
                    'id_order' => $order->id_order,
                    'id_cosmetic' => $item->medicine_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price
                ]);
            }
            
            // Cập nhật trạng thái đơn thuốc
            $prescription->prescription_status = 'completed';
            $prescription->processed_by = Auth::id();
            $prescription->processed_at = now();
            $prescription->save();
            
            DB::commit();
            
            return redirect()->route('pharmacist.prescriptions.pending')
                ->with('success', 'Đã xử lý đơn thuốc thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    // Quản lý kho
    public function inventoryIndex()
    {
        $medicines = Medicine::orderBy('name')->paginate(10);
        return view('pharmacist.inventory.index', compact('medicines'));
    }
    
    public function importForm()
    {
        $medicines = Medicine::orderBy('name')->get();
        return view('pharmacist.inventory.import', compact('medicines'));
    }
    
    public function processImport(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'batch_number' => 'required|string',
            'expiry_date' => 'required|date|after:today',
            'supplier' => 'required|string',
            'unit_price' => 'required|numeric|min:0'
        ]);
        
        DB::beginTransaction();
        
        try {
            $medicine = Medicine::findOrFail($request->medicine_id);
            
            // Cập nhật số lượng
            $medicine->stock_quantity += $request->quantity;
            $medicine->save();
            
            // Ghi log
            InventoryLog::create([
                'medicine_id' => $medicine->id,
                'quantity' => $request->quantity,
                'type' => 'in',
                'note' => "Nhập kho từ {$request->supplier}, lô: {$request->batch_number}",
                'user_id' => Auth::id(),
                'batch_number' => $request->batch_number,
                'expiry_date' => $request->expiry_date,
                'supplier' => $request->supplier,
                'unit_price' => $request->unit_price
            ]);
            
            DB::commit();
            
            return redirect()->route('pharmacist.inventory.index')
                ->with('success', "Đã nhập kho thành công {$request->quantity} {$medicine->name}");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function exportInventory()
    {
        $logs = InventoryLog::with('medicine', 'user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('pharmacist.inventory.export', compact('logs'));
    }
    
    // Quản lý đơn hàng
    public function ordersIndex()
    {
        $orders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('pharmacist.orders.index', compact('orders'));
    }
    
    public function createOrder()
    {
        $medicines = Medicine::where('stock_quantity', '>', 0)->orderBy('name')->get();
        $patients = User::where('id_role', 4)->orderBy('name')->get();
        
        return view('pharmacist.orders.create', compact('medicines', 'patients'));
    }
    
    public function storeOrder(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id_user',
            'payment_method' => 'required|in:cash,card,transfer',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);
        
        DB::beginTransaction();
        
        try {
            $totalPrice = 0;
            
            // Tính tổng tiền và kiểm tra tồn kho
            foreach ($request->items as $item) {
                $medicine = Medicine::findOrFail($item['medicine_id']);
                
                if ($medicine->stock_quantity < $item['quantity']) {
                    return redirect()->back()
                        ->with('error', "Thuốc {$medicine->name} không đủ số lượng trong kho!")
                        ->withInput();
                }
                
                $totalPrice += $medicine->price * $item['quantity'];
            }
            
            // Tạo đơn hàng
            $order = Order::create([
                'id_user' => $request->patient_id,
                'total_price' => $totalPrice,
                'payment_method' => $request->payment_method,
                'status' => 'completed'
            ]);
            
            // Thêm chi tiết đơn hàng và cập nhật tồn kho
            foreach ($request->items as $item) {
                $medicine = Medicine::findOrFail($item['medicine_id']);
                
                OrderItem::create([
                    'id_order' => $order->id_order,
                    'id_cosmetic' => $medicine->id,
                    'quantity' => $item['quantity'],
                    'price' => $medicine->price
                ]);
                
                // Cập nhật tồn kho
                $medicine->stock_quantity -= $item['quantity'];
                $medicine->save();
                
                // Ghi log
                InventoryLog::create([
                    'medicine_id' => $medicine->id,
                    'quantity' => -$item['quantity'],
                    'type' => 'out',
                    'note' => "Xuất cho đơn hàng #{$order->id_order}",
                    'user_id' => Auth::id()
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('pharmacist.orders.show', $order->id_order)
                ->with('success', 'Đã tạo đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function showOrder($id)
    {
        $order = Order::with(['user', 'orderItems.cosmetic'])
            ->findOrFail($id);
            
        return view('pharmacist.orders.show', compact('order'));
    }
    
    // Quản lý đổi trả
    public function returnsIndex()
    {
        $returns = ReturnOrder::with('order.user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('pharmacist.returns.index', compact('returns'));
    }
    
    public function createReturn($orderId)
    {
        $order = Order::with(['user', 'orderItems.cosmetic'])
            ->findOrFail($orderId);
            
        // Kiểm tra xem đơn hàng có thể đổi trả không
        if ($order->created_at->diffInDays(now()) > 7) {
            return redirect()->route('pharmacist.orders.show', $order->id_order)
                ->with('error', 'Đơn hàng đã quá thời gian đổi trả (7 ngày)!');
        }
        
        return view('pharmacist.returns.create', compact('order'));
    }
    
    public function storeReturn(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id_order',
            'reason' => 'required|string',
            'return_type' => 'required|in:refund,exchange',
            'items' => 'required|array|min:1',
            'items.*.order_item_id' => 'required|exists:order_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.return_reason' => 'required|string'
        ]);
        
        DB::beginTransaction();
        
        try {
            $order = Order::findOrFail($request->order_id);
            $totalRefund = 0;
            
            // Tạo đơn đổi trả
            $return = ReturnOrder::create([
                'order_id' => $order->id_order,
                'reason' => $request->reason,
                'return_type' => $request->return_type,
                'status' => 'completed',
                'processed_by' => Auth::id()
            ]);
            
            // Xử lý từng sản phẩm đổi trả
            foreach ($request->items as $item) {
                $orderItem = OrderItem::findOrFail($item['order_item_id']);
                
                // Kiểm tra số lượng
                if ($orderItem->quantity < $item['quantity']) {
                    throw new \Exception("Số lượng đổi trả không thể lớn hơn số lượng đã mua!");
                }
                
                // Tính tiền hoàn trả
                $refundAmount = $orderItem->price * $item['quantity'];
                $totalRefund += $refundAmount;
                
                // Thêm chi tiết đổi trả
                $return->items()->create([
                    'order_item_id' => $orderItem->id,
                    'quantity' => $item['quantity'],
                    'reason' => $item['return_reason'],
                    'refund_amount' => $refundAmount
                ]);
                
                // Cập nhật lại tồn kho
                $medicine = Medicine::findOrFail($orderItem->id_cosmetic);
                $medicine->stock_quantity += $item['quantity'];
                $medicine->save();
                
                // Ghi log
                InventoryLog::create([
                    'medicine_id' => $medicine->id,
                    'quantity' => $item['quantity'],
                    'type' => 'in',
                    'note' => "Nhập lại từ đơn đổi trả #{$return->id}",
                    'user_id' => Auth::id()
                ]);
            }
            
            // Cập nhật tổng tiền hoàn trả
            $return->total_refund = $totalRefund;
            $return->save();
            
            DB::commit();
            
            return redirect()->route('pharmacist.returns.show', $return->id)
                ->with('success', 'Đã xử lý đơn đổi trả thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function showReturn($id)
    {
        $return = ReturnOrder::with(['order.user', 'items.orderItem.cosmetic', 'processedBy'])
            ->findOrFail($id);
            
        return view('pharmacist.returns.show', compact('return'));
    }
}