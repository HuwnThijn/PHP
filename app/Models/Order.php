<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_order';
    protected $fillable = ['id_user', 'total_price', 'payment_method', 'status'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'id_order');
    }
    
    public function ship()
    {
        return $this->hasOne(Ship::class, 'id_order');
    }
    
    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'id_order');
    }
}
