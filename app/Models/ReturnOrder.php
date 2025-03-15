<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'reason', 'return_type', 'id_status', 
        'total_refund', 'processed_by', 'processed_at'
    ];

    protected $casts = [
        'total_refund' => 'decimal:2'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id_order');
    }

    public function items()
    {
        return $this->hasMany(ReturnItem::class, 'return_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by', 'id_user');
    }
} 