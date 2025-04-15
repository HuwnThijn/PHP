<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $order->id_order }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .company-info {
            float: left;
        }
        .invoice-info {
            float: right;
        }
        .logo {
            max-height: 80px;
            margin-bottom: 10px;
        }
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
        h1 {
            color: #4e73df;
            font-size: 24px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fc;
            color: #4e73df;
        }
        .customer-info, .shipping-info {
            width: 48%;
            float: left;
            margin-bottom: 20px;
        }
        .shipping-info {
            float: right;
        }
        .section-title {
            font-size: 14px;
            color: #4e73df;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .totals {
            width: 30%;
            float: right;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }
        .total-row.final {
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #ddd;
            padding-top: 10px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
        }
        .payment-info {
            margin: 20px 0;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 10px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
        }
        .badge-success {
            background-color: #1cc88a;
        }
        .badge-info {
            background-color: #36b9cc;
        }
        .badge-primary {
            background-color: #4e73df;
        }
        .badge-warning {
            background-color: #f6c23e;
            color: #000;
        }
        .badge-danger {
            background-color: #e74a3b;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .info-row label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header clearfix">
            <div class="company-info">
                <h2>COSMETIC SHOP</h2>
                <p>ĐC: 123 Đường ABC, Quận XYZ, TP.HCM</p>
                <p>SĐT: 0123 456 789</p>
                <p>Email: info@cosmeticshop.com</p>
            </div>
            <div class="invoice-info">
                <h1>HÓA ĐƠN</h1>
                <div class="info-row"><label>Mã hóa đơn:</label> #{{ $order->id_order }}</div>
                <div class="info-row"><label>Ngày lập:</label> {{ $order->created_at->format('d/m/Y H:i') }}</div>
                <div class="info-row">
                    <label>Trạng thái:</label>
                    @if($order->status == 'pending')
                        <span class="badge badge-warning">Chờ xử lý</span>
                    @elseif($order->status == 'confirmed')
                        <span class="badge badge-info">Đã xác nhận</span>
                    @elseif($order->status == 'shipped')
                        <span class="badge badge-primary">Đang vận chuyển</span>
                    @elseif($order->status == 'delivered')
                        <span class="badge badge-success">Đã giao hàng</span>
                    @elseif($order->status == 'cancelled')
                        <span class="badge badge-danger">Đã hủy</span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="customer-shipping-info clearfix">
            <div class="customer-info">
                <div class="section-title">THÔNG TIN KHÁCH HÀNG</div>
                <div class="info-row"><label>Tên khách hàng:</label> {{ $order->user->name ?? 'N/A' }}</div>
                <div class="info-row"><label>Email:</label> {{ $order->user->email ?? 'N/A' }}</div>
                <div class="info-row"><label>SĐT:</label> {{ $order->user->phone ?? 'N/A' }}</div>
            </div>
            
            <div class="shipping-info">
                <div class="section-title">THÔNG TIN GIAO HÀNG</div>
                <div class="info-row"><label>Người nhận:</label> {{ $order->shipping_name }}</div>
                <div class="info-row"><label>SĐT:</label> {{ $order->shipping_phone }}</div>
                <div class="info-row"><label>Địa chỉ:</label> {{ $order->shipping_address }}</div>
                <div class="info-row"><label>Phường/Xã:</label> {{ $order->shipping_ward }}</div>
                <div class="info-row"><label>Quận/Huyện:</label> {{ $order->shipping_district }}</div>
                <div class="info-row"><label>Tỉnh/TP:</label> {{ $order->shipping_province }}</div>
            </div>
        </div>
        
        <div class="payment-info clearfix">
            <div class="section-title">THÔNG TIN THANH TOÁN</div>
            <div class="info-row">
                <label>Phương thức:</label>
                @if($order->payment_method == 'cash')
                    Tiền mặt
                @elseif($order->payment_method == 'credit_card')
                    Thẻ tín dụng
                @elseif($order->payment_method == 'bank_transfer')
                    Chuyển khoản
                @else
                    {{ $order->payment_method }}
                @endif
            </div>
            <div class="info-row">
                <label>Trạng thái:</label>
                @if($order->payment_status == 'paid')
                    <span class="badge badge-success">Đã thanh toán</span>
                @else
                    <span class="badge badge-warning">Chưa thanh toán</span>
                @endif
            </div>
            @if($order->transaction_id)
            <div class="info-row"><label>Mã giao dịch:</label> {{ $order->transaction_id }}</div>
            @endif
        </div>
        
        <div class="section-title">CHI TIẾT ĐƠN HÀNG</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">STT</th>
                    <th style="width: 40%;">Sản phẩm</th>
                    <th style="width: 15%;" class="text-right">Đơn giá</th>
                    <th style="width: 15%;" class="text-center">Số lượng</th>
                    <th style="width: 20%;" class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $item->cosmetic->name ?? 'Sản phẩm không tồn tại' }}
                        @if($item->cosmetic)
                            <div style="font-size: 10px; color: #666;">Mã: {{ $item->cosmetic->id_cosmetic }}</div>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="totals">
            <div class="total-row">
                <span>Tạm tính:</span>
                <span>{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
            </div>
            <div class="total-row">
                <span>Phí vận chuyển:</span>
                <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
            </div>
            <div class="total-row">
                <span>Thuế:</span>
                <span>{{ number_format($order->tax, 0, ',', '.') }}đ</span>
            </div>
            @if($order->discount > 0)
            <div class="total-row">
                <span>Giảm giá:</span>
                <span>-{{ number_format($order->discount, 0, ',', '.') }}đ</span>
            </div>
            @endif
            <div class="total-row final">
                <span>TỔNG TIỀN:</span>
                <span>{{ number_format($order->total_price, 0, ',', '.') }}đ</span>
            </div>
        </div>
        
        <div class="footer">
            <p>Cảm ơn quý khách đã mua hàng tại Cosmetic Shop!</p>
            <p>Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua số điện thoại: 0123 456 789</p>
            <p>Hóa đơn được tạo ngày {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html> 