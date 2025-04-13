<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_order';
    protected $fillable = [
        'id_user', 
        'total_price', 
        'payment_method', 
        'status',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
        'cancellation_reason',
        'payment_status',
        'subtotal',
        'shipping_fee',
        'tax',
        'discount',
        'transaction_id',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_ward',
        'shipping_district',
        'shipping_province',
        'notes'
    ];
    
    protected $casts = [
        'total_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime'
    ];
    
    protected $attributes = [
        'status' => 'pending'
    ];

    /**
     * Các trạng thái có thể có của đơn hàng
     */
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    
    /**
     * Các phương thức thanh toán
     */
    const PAYMENT_CASH = 'cash';
    const PAYMENT_CARD = 'credit_card';
    const PAYMENT_TRANSFER = 'bank_transfer';
    
    /**
     * Các trạng thái thanh toán
     */
    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_FAILED = 'failed';
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'id_order', 'id_order');
    }
    
    public function ship()
    {
        return $this->hasOne(Ship::class, 'id_order', 'id_order');
    }
    
    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'id_order', 'id_order');
    }
}
