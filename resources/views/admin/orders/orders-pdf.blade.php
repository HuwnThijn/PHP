<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách đơn hàng</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
        }
        h1 {
            font-size: 18px;
            text-align: center;
            margin-bottom: 10px;
            color: #4e73df;
        }
        h2 {
            font-size: 14px;
            margin-bottom: 8px;
            color: #4e73df;
        }
        .header {
            margin-bottom: 20px;
            text-align: center;
        }
        .company-info {
            margin-bottom: 15px;
        }
        .filter-info {
            margin-bottom: 15px;
            font-size: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 5px;
            font-size: 10px;
            text-align: left;
        }
        table th {
            background-color: #f8f9fc;
            font-weight: bold;
            color: #4e73df;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: center;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 9px;
            text-align: center;
            color: white;
            font-weight: bold;
        }
        .status-pending {
            background-color: #f6c23e;
            color: #000;
        }
        .status-confirmed {
            background-color: #36b9cc;
        }
        .status-shipped {
            background-color: #4e73df;
        }
        .status-delivered {
            background-color: #1cc88a;
        }
        .status-cancelled {
            background-color: #e74a3b;
        }
        .summary {
            margin-top: 15px;
            margin-bottom: 15px;
        }
        .summary-table {
            width: auto;
            margin-left: auto;
            float: right;
        }
        .summary-table th, .summary-table td {
            padding: 3px 8px;
        }
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DANH SÁCH ĐƠN HÀNG</h1>
        <div class="company-info">
            <div><strong>COSMETIC SHOP</strong></div>
            <div>ĐC: 123 Đường ABC, Quận XYZ, TP.HCM</div>
            <div>SĐT: 0123 456 789 | Email: info@cosmeticshop.com</div>
        </div>
        
        <div class="filter-info">
            <strong>Bộ lọc:</strong>
            Trạng thái: {{ $statusFilter ? ucfirst($statusFilter) : 'Tất cả' }} |
            Từ ngày: {{ $dateFrom ? date('d/m/Y', strtotime($dateFrom)) : 'Tất cả' }} |
            Đến ngày: {{ $dateTo ? date('d/m/Y', strtotime($dateTo)) : 'Tất cả' }}
        </div>
    </div>
    
    <div class="summary clearfix">
        <h2>Thống kê</h2>
        <table class="summary-table">
            <tr>
                <th>Tổng số đơn hàng:</th>
                <td class="text-right">{{ $orders->count() }}</td>
            </tr>
            <tr>
                <th>Đơn chờ xử lý:</th>
                <td class="text-right">{{ $orders->where('status', 'pending')->count() }}</td>
            </tr>
            <tr>
                <th>Đơn đã hoàn thành:</th>
                <td class="text-right">{{ $orders->where('status', 'delivered')->count() }}</td>
            </tr>
            <tr>
                <th>Đơn đã hủy:</th>
                <td class="text-right">{{ $orders->where('status', 'cancelled')->count() }}</td>
            </tr>
            <tr>
                <th>Tổng doanh thu:</th>
                <td class="text-right">{{ number_format($totalRevenue, 0, ',', '.') }}đ</td>
            </tr>
        </table>
    </div>
    
    <h2>Chi tiết đơn hàng</h2>
    <table>
        <thead>
            <tr>
                <th>Mã ĐH</th>
                <th>Khách hàng</th>
                <th>Liên hệ</th>
                <th>Ngày đặt</th>
                <th>Trạng thái</th>
                <th>TT thanh toán</th>
                <th>PT thanh toán</th>
                <th class="text-right">Tổng tiền</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>{{ $order->id_order }}</td>
                <td>{{ $order->user->name ?? 'N/A' }}</td>
                <td>
                    {{ $order->user->email ?? 'N/A' }}<br>
                    {{ $order->user->phone ?? 'N/A' }}
                </td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @if($order->status == 'pending')
                        <div class="status-badge status-pending">Chờ xử lý</div>
                    @elseif($order->status == 'confirmed')
                        <div class="status-badge status-confirmed">Đã xác nhận</div>
                    @elseif($order->status == 'shipped')
                        <div class="status-badge status-shipped">Đang vận chuyển</div>
                    @elseif($order->status == 'delivered')
                        <div class="status-badge status-delivered">Đã giao hàng</div>
                    @elseif($order->status == 'cancelled')
                        <div class="status-badge status-cancelled">Đã hủy</div>
                    @else
                        {{ $order->status }}
                    @endif
                </td>
                <td class="text-center">
                    @if($order->payment_status == 'paid')
                        <div class="status-badge status-delivered">Đã thanh toán</div>
                    @else
                        <div class="status-badge status-pending">Chưa thanh toán</div>
                    @endif
                </td>
                <td>
                    @if($order->payment_method == 'cash')
                        Tiền mặt
                    @elseif($order->payment_method == 'credit_card')
                        Thẻ tín dụng
                    @elseif($order->payment_method == 'bank_transfer')
                        Chuyển khoản
                    @else
                        {{ $order->payment_method }}
                    @endif
                </td>
                <td class="text-right">{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Không có đơn hàng nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Báo cáo được tạo lúc: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>© {{ date('Y') }} Cosmetic Shop. Mọi quyền được bảo lưu.</p>
    </div>
</body>
</html> 