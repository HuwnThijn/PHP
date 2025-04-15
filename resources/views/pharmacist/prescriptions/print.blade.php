<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn thuốc #{{ $prescription->id_prescription }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .prescription-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .clinic-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .clinic-info {
            font-size: 14px;
            margin-bottom: 3px;
        }
        .prescription-title {
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            text-transform: uppercase;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 16px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        .info-label {
            width: 150px;
            font-weight: bold;
        }
        .info-value {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .total-row {
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .signature {
            margin-top: 80px;
            font-weight: bold;
        }
        .logo {
            max-width: 100px;
            margin-bottom: 10px;
        }
        .barcode {
            text-align: right;
            margin-bottom: 20px;
        }
        .notes {
            padding: 10px;
            border: 1px dashed #ccc;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }
        
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
            @page {
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()">In đơn thuốc</button>
        <button onclick="window.close()">Đóng</button>
    </div>

    <div class="prescription-header">
        <div class="clinic-name">PHÒNG KHÁM O2 Skin</div>
        <div class="clinic-info">Địa chỉ: Thủ Đức, TP Hồ Chí Minh</div>
        <div class="clinic-info">Điện thoại: (024) 3123 4567 | Email: info@o2skin.com</div>
    </div>

    <div class="prescription-title">ĐƠN THUỐC</div>
    
    <div class="barcode">
        Mã đơn: #{{ $prescription->id_prescription }}
    </div>

    <div class="section">
        <div class="section-title">THÔNG TIN BỆNH NHÂN</div>
        <div class="info-row">
            <div class="info-label">Họ và tên:</div>
            <div class="info-value">{{ $prescription->patient->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Điện thoại:</div>
            <div class="info-value">{{ $prescription->patient->phone }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Địa chỉ:</div>
            <div class="info-value">{{ $prescription->patient->address }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">THÔNG TIN BÁC SĨ</div>
        <div class="info-row">
            <div class="info-label">Bác sĩ kê đơn:</div>
            <div class="info-value">{{ $prescription->doctor->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Chuyên môn:</div>
            <div class="info-value">{{ $prescription->doctor->specialization }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">DANH SÁCH THUỐC</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">STT</th>
                    <th style="width: 25%">Tên thuốc</th>
                    <th style="width: 10%">Số lượng</th>
                    <th style="width: 30%">Liều dùng</th>
                    <th style="width: 30%">Hướng dẫn</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prescription->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->medicine->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->dosage }}</td>
                    <td>{{ $item->instructions }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($prescription->notes)
    <div class="section">
        <div class="section-title">GHI CHÚ CỦA BÁC SĨ</div>
        <div class="notes">
            {{ $prescription->notes }}
        </div>
    </div>
    @endif

    <div class="section">
        <div class="section-title">THÔNG TIN THANH TOÁN</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">STT</th>
                    <th style="width: 45%">Tên thuốc</th>
                    <th style="width: 10%">Số lượng</th>
                    <th style="width: 20%">Đơn giá</th>
                    <th style="width: 20%">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($prescription->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->medicine->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price) }} VNĐ</td>
                    <td>{{ number_format($item->price * $item->quantity) }} VNĐ</td>
                    @php $total += $item->price * $item->quantity; @endphp
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4" style="text-align: right">Tổng cộng:</td>
                    <td>{{ number_format($total) }} VNĐ</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <div>Ngày xuất: {{ now()->format('d/m/Y') }}</div>
        <div>Người xử lý: {{ $prescription->processedBy->name ?? auth()->user()->name }}</div>
        <div>Phương thức thanh toán: 
            @if($prescription->payment_method == 'cash')
                Tiền mặt
            @elseif($prescription->payment_method == 'card')
                Thẻ (Mã GD: {{ $prescription->payment_id }})
            @elseif($prescription->payment_method == 'transfer')
                Chuyển khoản
            @else
                {{ $prescription->payment_method ?? 'N/A' }}
            @endif
        </div>
        <div class="signature">Chữ ký</div>
    </div>
</body>
</html> 