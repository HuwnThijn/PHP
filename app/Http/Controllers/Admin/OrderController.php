<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ship;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\PDF;

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách tất cả đơn hàng
     */
    public function index(Request $request)
    {
        $query = Order::query()->with(['user']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Search by order id or customer name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id_order', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%")
                         ->orWhere('phone', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Get order stats
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'delivered')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        
        // Calculate total revenue
        $totalRevenue = Order::where('status', 'delivered')->sum('total_price');
        
        // Get orders with pagination
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.orders.index', compact(
            'orders', 
            'totalOrders', 
            'pendingOrders', 
            'completedOrders',
            'deliveredOrders', 
            'cancelledOrders',
            'totalRevenue'
        ));
    }
    
    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::with(['user', 'orderItems.cosmetic', 'ship'])->findOrFail($id);
        
        // Nếu thông tin giao hàng đến từ bảng ship, sao chép sang các trường của order
        if ($order->ship && (!$order->shipping_name || !$order->shipping_address)) {
            $order->shipping_address = $order->ship->address;
            $order->shipping_fee = $order->ship->shipping_fee;
            
            // Lưu lại thông tin nếu chưa có
            if (!$order->shipping_address) {
                $order->save();
            }
        }
        
        return view('admin.orders.show', compact('order'));
    }
    
    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:confirmed,shipped,delivered,cancelled',
            'cancellation_reason' => 'required_if:status,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;
        
        // Update the order status
        $order->status = $newStatus;
        
        // Update timestamps based on status
        if ($newStatus == 'confirmed' && $oldStatus == 'pending') {
            $order->confirmed_at = now();
        } elseif ($newStatus == 'shipped' && in_array($oldStatus, ['pending', 'confirmed'])) {
            $order->shipped_at = now();
        } elseif ($newStatus == 'delivered' && in_array($oldStatus, ['pending', 'confirmed', 'shipped'])) {
            $order->delivered_at = now();
            $order->payment_status = 'paid'; // Mark as paid if delivered
        } elseif ($newStatus == 'cancelled') {
            $order->cancellation_reason = $request->cancellation_reason;
        }
        
        $order->save();
        
        // Send notification to user if needed
        // TODO: Implement notification logic
        
        return redirect()->route('admin.orders.show', $id)
            ->with('success', 'Trạng thái đơn hàng đã được cập nhật thành công.');
    }
    
    /**
     * Xuất danh sách đơn hàng ra Excel
     */
    public function exportExcel(Request $request)
    {
        // Tạo query dựa trên bộ lọc
        $query = Order::with(['user', 'orderItems'])
            ->orderBy('created_at', 'desc');
        
        // Áp dụng bộ lọc (tương tự như trong index)
        $statusFilter = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }
        
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        // Lấy kết quả
        $orders = $query->get();
        
        // Tạo tên file với timestamp
        $filename = 'don-hang-' . date('Y-m-d-H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            // Thêm BOM để hỗ trợ Unicode
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Thêm header CSV
            fputcsv($file, [
                'Mã đơn hàng', 
                'Khách hàng', 
                'Email', 
                'SĐT', 
                'Ngày đặt', 
                'Trạng thái', 
                'Phương thức thanh toán', 
                'Trạng thái thanh toán', 
                'Tổng tiền'
            ]);
            
            // Thêm dữ liệu đơn hàng
            foreach ($orders as $order) {
                $status = [
                    'pending' => 'Chờ xử lý',
                    'confirmed' => 'Đã xác nhận',
                    'shipped' => 'Đang vận chuyển',
                    'delivered' => 'Đã giao hàng',
                    'cancelled' => 'Đã hủy'
                ][$order->status] ?? $order->status;
                
                $paymentMethod = [
                    'cash' => 'Tiền mặt',
                    'credit_card' => 'Thẻ tín dụng',
                    'bank_transfer' => 'Chuyển khoản'
                ][$order->payment_method] ?? $order->payment_method;
                
                $paymentStatus = [
                    'paid' => 'Đã thanh toán',
                    'pending' => 'Chưa thanh toán'
                ][$order->payment_status] ?? $order->payment_status;
                
                fputcsv($file, [
                    $order->id_order,
                    $order->user->name ?? 'N/A',
                    $order->user->email ?? 'N/A',
                    $order->user->phone ?? 'N/A',
                    $order->created_at->format('d/m/Y H:i'),
                    $status,
                    $paymentMethod,
                    $paymentStatus,
                    number_format($order->total_price, 0, ',', '.').'đ'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Xuất danh sách đơn hàng ra PDF
     */
    public function exportPdf(Request $request)
    {
        // Tạo query dựa trên bộ lọc
        $query = Order::with(['user', 'orderItems'])
            ->orderBy('created_at', 'desc');
        
        // Áp dụng bộ lọc (tương tự như trong index)
        $statusFilter = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }
        
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        // Lấy kết quả
        $orders = $query->get();
        
        // Tính tổng doanh thu
        $totalRevenue = $orders->where('status', 'delivered')->sum('total_price');
        
        // Tạo PDF
        $pdf = PDF::loadView('admin.orders.orders-pdf', compact('orders', 'totalRevenue', 'statusFilter', 'dateFrom', 'dateTo'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('danh-sach-don-hang-' . date('Y-m-d-H-i-s') . '.pdf');
    }

    public function invoice($id)
    {
        $order = Order::with(['user', 'orderItems.cosmetic'])->findOrFail($id);
        
        $pdf = PDF::loadView('admin.orders.invoice', compact('order'));
        return $pdf->stream('invoice-' . $order->id_order . '.pdf');
    }

    public function export(Request $request)
    {
        $query = Order::query()->with(['user', 'orderItems.cosmetic']);

        // Apply the same filters as in index method
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id_order', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%")
                         ->orWhere('phone', 'LIKE', "%{$search}%");
                  });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->get();
        
        // Create CSV file
        $filename = 'orders-' . Carbon::now()->format('Y-m-d-H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add CSV header
            fputcsv($file, [
                'ID', 
                'Khách hàng', 
                'Email', 
                'SĐT', 
                'Ngày đặt', 
                'Trạng thái', 
                'Phương thức thanh toán', 
                'Trạng thái thanh toán', 
                'Tổng tiền'
            ]);
            
            // Add order data
            foreach ($orders as $order) {
                $status = [
                    'pending' => 'Chờ xử lý',
                    'confirmed' => 'Đã xác nhận',
                    'shipped' => 'Đang vận chuyển',
                    'delivered' => 'Đã giao hàng',
                    'cancelled' => 'Đã hủy'
                ][$order->status] ?? $order->status;
                
                $paymentMethod = [
                    'cash' => 'Tiền mặt',
                    'credit_card' => 'Thẻ tín dụng',
                    'bank_transfer' => 'Chuyển khoản'
                ][$order->payment_method] ?? $order->payment_method;
                
                $paymentStatus = [
                    'paid' => 'Đã thanh toán',
                    'pending' => 'Chưa thanh toán'
                ][$order->payment_status] ?? $order->payment_status;
                
                fputcsv($file, [
                    $order->id_order,
                    $order->user->name ?? 'N/A',
                    $order->user->email ?? 'N/A',
                    $order->user->phone ?? 'N/A',
                    $order->created_at->format('d/m/Y H:i'),
                    $status,
                    $paymentMethod,
                    $paymentStatus,
                    number_format($order->total_price, 0, ',', '.').'đ'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function dashboard()
    {
        // Get order stats for dashboard
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        
        // Get recent orders
        $recentOrders = Order::with('user')
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
        
        // Get monthly revenue for chart (last 6 months)
        $months = [];
        $revenue = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M');
            
            $monthlyRevenue = Order::whereMonth('created_at', $month->month)
                                ->whereYear('created_at', $month->year)
                                ->where('status', 'delivered')
                                ->sum('total_price');
            
            $revenue[] = $monthlyRevenue;
        }
        
        return view('admin.orders.dashboard', compact(
            'totalOrders', 
            'pendingOrders', 
            'deliveredOrders', 
            'cancelledOrders',
            'recentOrders',
            'months',
            'revenue'
        ));
    }
} 