<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_order_item';
    protected $fillable = ['id_order', 'id_cosmetic', 'quantity', 'unit_price'];
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }
    
    public function cosmetic()
    {
        return $this->belongsTo(Cosmetic::class, 'id_cosmetic');
    }
}
