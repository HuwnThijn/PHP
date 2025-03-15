<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_id', 'order_item_id', 'quantity', 'reason', 'refund_amount'
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2'
    ];

    public function returnOrder()
    {
        return $this->belongsTo(ReturnOrder::class, 'return_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
} 