<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ship extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_ship';
    protected $fillable = ['id_order', 'address', 'distance', 'shipping_fee', 'status'];
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }
}
