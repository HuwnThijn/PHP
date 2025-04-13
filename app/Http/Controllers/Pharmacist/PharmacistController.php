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
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\PrescriptionItem;
use App\Models\InventoryTransaction;
use App\Models\Doctor;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use App\Models\Schedule;

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
        
        $recentPrescriptions = Prescription::with('patient', 'doctor')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('pharmacist.dashboard', compact(
            'pendingPrescriptions', 'lowStockItems', 'recentPrescriptions'
        ));
    }
    
    // Quản lý đơn thuốc
    public function pendingPrescriptions()
    {
        // Lấy tất cả đơn thuốc thay vì lọc theo trạng thái
        $prescriptions = Prescription::with(['patient', 'doctor', 'items.medicine'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('pharmacist.prescriptions.pending', compact('prescriptions'));
    }
    
    // Tiếp nhận bệnh nhân và tạo đơn thuốc
    public function receivePatient()
    {
        // Lấy danh sách các cuộc hẹn đã lên lịch trong ngày
        $todayAppointments = Appointment::with(['patient', 'doctor', 'service'])
            ->where('status', 'scheduled')
            ->whereDate('appointment_time', Carbon::today())
            ->orderBy('appointment_time')
            ->get();
        
        // Lấy danh sách bác sĩ có lịch làm việc hôm nay
        $today = Carbon::today()->format('Y-m-d');
        $availableDoctors = Schedule::getDoctorsAvailableOn($today);
        
        return view('pharmacist.receive-patient', compact('todayAppointments', 'availableDoctors'));
    }
    
    // Xử lý việc nhận bệnh nhân từ lịch hẹn
    public function processPatient(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'nullable|exists:appointments,id_appointment',
            'patient_id' => 'required|exists:users,id_user',
            'doctor_id' => 'required|exists:users,id_user',
            'notes' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        
        try {
            // Nếu có ID lịch hẹn, cập nhật trạng thái lịch hẹn
            if (!empty($validated['appointment_id'])) {
                $appointment = Appointment::findOrFail($validated['appointment_id']);
                $appointment->status = 'completed';
                $appointment->save();
            }
            
            // Tạo hồ sơ bệnh án mới
            $medicalRecord = MedicalRecord::create([
                'id_patient' => $validated['patient_id'],
                'id_doctor' => $validated['doctor_id'],
                'notes' => $validated['notes'] ?? null,
                'diagnosis' => null, // Sẽ được cập nhật bởi bác sĩ
            ]);
            
            DB::commit();
            
            // Thông báo cho bác sĩ về bệnh nhân mới (có thể thông qua notification hoặc realtime)
            // ...
            
            return redirect()->route('pharmacist.dashboard')
                ->with('success', 'Đã gửi thông tin bệnh nhân tới bác sĩ thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    // Nhận đơn thuốc từ bác sĩ
    public function receivePrescription($medicalRecordId)
    {
        $medicalRecord = MedicalRecord::with(['patient', 'doctor', 'prescriptions'])
            ->findOrFail($medicalRecordId);
        
        $medicines = Medicine::where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get();
        
        return view('pharmacist.process-prescription', compact('medicalRecord', 'medicines'));
    }
    
    // Hoàn thành quá trình xử lý đơn thuốc và thanh toán
    public function completePrescription(Request $request, $medicalRecordId)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,credit_card,bank_transfer'
        ]);
        
        DB::beginTransaction();
        
        try {
            $medicalRecord = MedicalRecord::with('patient')
                ->findOrFail($medicalRecordId);
            
            // Tạo đơn thuốc
            $prescription = Prescription::create([
                'id_patient' => $medicalRecord->id_patient,
                'id_doctor' => $medicalRecord->id_doctor,
                'diagnosis' => $medicalRecord->diagnosis,
                'notes' => $request->notes,
                'status' => 'completed',
                'processed_by' => Auth::id(),
                'processed_at' => now()
            ]);
            
            $totalAmount = 0;
            
            // Thêm thuốc vào đơn thuốc
            foreach ($validated['items'] as $item) {
                $medicine = Medicine::findOrFail($item['medicine_id']);
                
                // Kiểm tra tồn kho
                if ($medicine->stock_quantity < $item['quantity']) {
                    throw new \Exception("Thuốc {$medicine->name} không đủ số lượng trong kho!");
                }
                
                // Tính giá
                $price = $medicine->price;
                $amount = $price * $item['quantity'];
                $totalAmount += $amount;
                
                // Thêm chi tiết đơn thuốc
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id_prescription,
                    'medicine_id' => $medicine->id,
                    'quantity' => $item['quantity'],
                    'dosage' => $request->input("dosage.{$medicine->id}", ''),
                    'instructions' => $request->input("instructions.{$medicine->id}", ''),
                    'price' => $price
                ]);
                
                // Cập nhật tồn kho
                $medicine->stock_quantity -= $item['quantity'];
                $medicine->save();
                
                // Ghi log
                InventoryLog::create([
                    'medicine_id' => $medicine->id,
                    'quantity' => -$item['quantity'],
                    'type' => 'out',
                    'note' => "Xuất cho đơn thuốc #{$prescription->id_prescription}",
                    'user_id' => Auth::id()
                ]);
            }
            
            // Cập nhật tổng tiền
            $prescription->total_amount = $totalAmount;
            $prescription->save();
            
            // Tạo đơn hàng
            try {
                $order = Order::create([
                    'id_user' => $medicalRecord->id_patient,
                    'total_price' => $totalAmount,
                    'payment_method' => $validated['payment_method'],
                    'status' => 'done'
                ]);
            } catch (\PDOException $e) {
                // Xử lý lỗi SQLSTATE[01000]: Warning: 1265 Data truncated
                if (str_contains($e->getMessage(), 'SQLSTATE[01000]')) {
                    // Thử lại với giá trị ngắn hơn
                    $order = Order::create([
                        'id_user' => $medicalRecord->id_patient,
                        'total_price' => $totalAmount,
                        'payment_method' => $validated['payment_method'],
                        'status' => 'ok' // Giá trị ngắn hơn
                    ]);
                } else {
                    throw $e; // Ném lại nếu lỗi khác
                }
            }
            
            // Thêm chi tiết đơn hàng
            foreach ($validated['items'] as $item) {
                $medicine = Medicine::find($item['medicine_id']);
                OrderItem::create([
                    'id_order' => $order->id_order,
                    'id_cosmetic' => $medicine->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $medicine->price
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('pharmacist.prescriptions.pending')
                ->with('success', 'Đã hoàn tất xử lý đơn thuốc và thanh toán thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Ẩn thông báo lỗi SQL cụ thể
            if (str_contains($e->getMessage(), 'SQLSTATE[01000]')) {
                return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xử lý đơn hàng. Vui lòng thử lại!');
            }
            
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
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
        $prescription = Prescription::with(['patient', 'doctor', 'items.medicine', 'processedBy'])
            ->findOrFail($id);
            
        return view('pharmacist.prescriptions.show', compact('prescription'));
    }
    
    public function processPrescription(Request $request, $id)
    {
        $prescription = Prescription::where('id_prescription', $id)
            ->with(['patient', 'doctor', 'items.medicine'])
            ->firstOrFail();

        if ($prescription->status !== 'pending') {
            return redirect()->back()->with('error', 'Đơn thuốc này đã được xử lý!');
        }

        // Kiểm tra xem có thanh toán qua Stripe hay không
        if ($request->payment_method === 'card' && !$request->has('stripe_payment_id')) {
            return redirect()->back()->with('error', 'Thanh toán bằng thẻ không thành công. Vui lòng thử lại.');
        }

        // Update inventory
        $inventoryUpdated = true;
        $errors = [];

        foreach ($prescription->items as $item) {
            $medicine = $item->medicine;
            
            if ($medicine->stock_quantity >= $item->quantity) {
                $medicine->stock_quantity -= $item->quantity;
                $medicine->save();
                
                // Ghi log giao dịch
                InventoryLog::create([
                    'medicine_id' => $medicine->id,
                    'quantity' => -$item->quantity,
                    'type' => 'out',
                    'note' => "Xuất cho đơn thuốc #{$prescription->id_prescription}",
                    'user_id' => Auth::id()
                ]);
            } else {
                $inventoryUpdated = false;
                $errors[] = "Không đủ số lượng thuốc {$medicine->name} trong kho (Còn: {$medicine->stock_quantity}, Cần: {$item->quantity})";
            }
        }

        if (!$inventoryUpdated) {
            return redirect()->back()->with('error', implode('<br>', $errors));
        }

        // Update prescription status with DB transaction
        DB::beginTransaction();
        try {
            // Cập nhật từng trường một để tránh lỗi
            DB::table('prescriptions')
                ->where('id_prescription', $id)
                ->update([
                    'status' => 'completed',
                    'processed_at' => now(),
                    'processed_by' => Auth::id(),
                    'payment_method' => $request->payment_method,
                    'payment_status' => $request->has('stripe_payment_id') ? 'paid' : 'completed',
                    'payment_id' => $request->stripe_payment_id ?? null
                ]);
            
            DB::commit();
            
            return redirect()->route('pharmacist.prescriptions.show', $prescription->id_prescription)
                ->with('success', 'Đơn thuốc đã được xử lý thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi lưu thông tin: ' . $e->getMessage());
        }
    }
    
    /**
     * Tạo payment intent cho thanh toán qua Stripe
     */
    public function createPaymentIntent($id)
    {
        try {
            $prescription = Prescription::where('id_prescription', $id)
                ->with(['items.medicine', 'patient'])
                ->firstOrFail();
                
            if ($prescription->status !== 'pending') {
                return response()->json(['error' => 'Đơn thuốc này đã được xử lý!'], 400);
            }

            // Tính tổng tiền
            $amount = 0;
            foreach ($prescription->items as $item) {
                $amount += $item->price * $item->quantity;
            }

            // Kiểm tra tiền thanh toán hợp lệ
            if ($amount <= 0) {
                return response()->json(['error' => 'Số tiền thanh toán không hợp lệ'], 400);
            }

            // Cấu hình Stripe API key
            $stripeSecret = config('services.stripe.secret');
            if (empty($stripeSecret)) {
                Log::error('Stripe Secret Key không được cấu hình');
                return response()->json(['error' => 'Lỗi cấu hình thanh toán'], 500);
            }
            
            Stripe::setApiKey($stripeSecret);
            
            try {
                // Chuyển đổi VND sang USD (tỉ giá xấp xỉ: 1 USD = 25,000 VND)
                $amountUSD = round($amount / 25000, 2);
                
                // Kiểm tra giới hạn số tiền tối thiểu
                if ($amountUSD < 0.5) {
                    $amountUSD = 0.5; // Stripe yêu cầu giao dịch tối thiểu 0.5 USD
                }
                
                // Tạo payment intent
                $paymentIntent = PaymentIntent::create([
                    'amount' => (int)($amountUSD * 100), // Stripe tính bằng xu (100 xu = 1 đô), đảm bảo là số nguyên
                    'currency' => 'usd',
                    'description' => 'Thanh toán đơn thuốc #' . $prescription->id_prescription,
                    'metadata' => [
                        'prescription_id' => $prescription->id_prescription,
                        'patient_id' => $prescription->id_patient,
                        'patient_name' => $prescription->patient->name ?? 'Không xác định',
                        'original_amount_vnd' => $amount
                    ]
                ]);
                
                return response()->json([
                    'clientSecret' => $paymentIntent->client_secret,
                    'amount_usd' => (int)($amountUSD * 100),
                    'amount_vnd' => $amount
                ]);
            } catch (ApiErrorException $e) {
                Log::error('Stripe API Error: ' . $e->getMessage());
                return response()->json(['error' => 'Lỗi API Stripe: ' . $e->getMessage()], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Payment Intent Error: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra khi tạo phiên thanh toán: ' . $e->getMessage()], 500);
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
            try {
                $order = Order::create([
                    'id_user' => $request->patient_id,
                    'total_price' => $totalPrice,
                    'payment_method' => $request->payment_method,
                    'status' => 'done'
                ]);
            } catch (\PDOException $e) {
                // Xử lý lỗi SQLSTATE[01000]: Warning: 1265 Data truncated
                if (str_contains($e->getMessage(), 'SQLSTATE[01000]')) {
                    // Thử lại với giá trị ngắn hơn
                    $order = Order::create([
                        'id_user' => $request->patient_id,
                        'total_price' => $totalPrice,
                        'payment_method' => $request->payment_method,
                        'status' => 'ok' // Giá trị ngắn hơn
                    ]);
                } else {
                    throw $e; // Ném lại nếu lỗi khác
                }
            }
            
            // Thêm chi tiết đơn hàng và cập nhật tồn kho
            foreach ($request->items as $item) {
                $medicine = Medicine::findOrFail($item['medicine_id']);
                
                OrderItem::create([
                    'id_order' => $order->id_order,
                    'id_cosmetic' => $medicine->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $medicine->price
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
            
            // Ẩn thông báo lỗi SQL cụ thể
            if (str_contains($e->getMessage(), 'SQLSTATE[01000]')) {
                return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xử lý đơn hàng. Vui lòng thử lại!');
            }
            
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
            try {
                $return = ReturnOrder::create([
                    'order_id' => $order->id_order,
                    'reason' => $request->reason,
                    'return_type' => $request->return_type,
                    'status' => 'done',
                    'processed_by' => Auth::id()
                ]);
            } catch (\PDOException $e) {
                // Xử lý lỗi SQLSTATE[01000]: Warning: 1265 Data truncated
                if (str_contains($e->getMessage(), 'SQLSTATE[01000]')) {
                    // Thử lại với giá trị ngắn hơn
                    $return = ReturnOrder::create([
                        'order_id' => $order->id_order,
                        'reason' => $request->reason,
                        'return_type' => $request->return_type,
                        'status' => 'ok',  // Giá trị ngắn hơn
                        'processed_by' => Auth::id()
                    ]);
                } else {
                    throw $e; // Ném lại nếu lỗi khác
                }
            }
            
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
            
            // Ẩn thông báo lỗi SQL cụ thể
            if (str_contains($e->getMessage(), 'SQLSTATE[01000]')) {
                return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xử lý đơn đổi trả. Vui lòng thử lại!');
            }
            
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

    public function create()
    {
        $today = now()->format('Y-m-d');
        $doctors = User::where('id_role', 2)
            ->whereHas('schedules', function($query) use ($today) {
                $query->where('date', $today)
                    ->where('is_available', true);
            })
            ->get();

        return view('pharmacist.patients.create', compact('doctors'));
    }
    
    public function printPrescription($id)
    {
        $prescription = Prescription::where('id_prescription', $id)
            ->with(['patient', 'doctor', 'items.medicine', 'processedBy'])
            ->firstOrFail();
            
        return view('pharmacist.prescriptions.print', compact('prescription'));
    }
}