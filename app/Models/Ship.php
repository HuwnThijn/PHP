<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ship extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_ship';
    protected $fillable = ['id_order', 'address', 'distance', 'shipping_fee', 'status'];
    
    /**
     * Các trạng thái có thể có của đơn vận chuyển
     */
    const STATUS_PENDING = 'pending';
    const STATUS_SHIPPING = 'shipping';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_FAILED = 'failed';
    
    /**
     * Các giá trị mặc định cho model
     */
    protected $attributes = [
        'status' => 'pending'
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }
}
