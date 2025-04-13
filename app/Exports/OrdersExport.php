<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->orders;
    }
    
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Mã đơn hàng',
            'Ngày đặt',
            'Khách hàng',
            'Email',
            'Số sản phẩm',
            'Tổng tiền',
            'Phương thức thanh toán',
            'Trạng thái'
        ];
    }
    
    /**
     * @param mixed $order
     * @return array
     */
    public function map($order): array
    {
        // Format trạng thái đơn hàng
        $status = '';
        switch ($order->status) {
            case 'pending':
                $status = 'Chờ xử lý';
                break;
            case 'confirmed':
                $status = 'Đã xác nhận';
                break;
            case 'shipped':
                $status = 'Đang vận chuyển';
                break;
            case 'delivered':
                $status = 'Đã giao hàng';
                break;
            case 'cancelled':
                $status = 'Đã hủy';
                break;
            default:
                $status = $order->status;
        }
        
        // Format phương thức thanh toán
        $paymentMethod = '';
        switch ($order->payment_method) {
            case 'cash':
                $paymentMethod = 'Tiền mặt';
                break;
            case 'credit_card':
                $paymentMethod = 'Thẻ tín dụng';
                break;
            case 'bank_transfer':
                $paymentMethod = 'Chuyển khoản';
                break;
            default:
                $paymentMethod = $order->payment_method;
        }
        
        return [
            'ORD-' . str_pad($order->id_order, 4, '0', STR_PAD_LEFT),
            $order->created_at->format('d/m/Y H:i'),
            $order->user->name ?? 'N/A',
            $order->user->email ?? 'N/A',
            $order->orderItems->sum('quantity'),
            number_format($order->total_price, 0, ',', '.') . ' đ',
            $paymentMethod,
            $status
        ];
    }
    
    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold
            1 => ['font' => ['bold' => true]],
        ];
    }
} 