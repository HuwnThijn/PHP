<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_transaction';
    protected $fillable = ['id_user', 'id_order', 'amount', 'points_earned', 'points_used', 'payment_method', 'transaction_date', 'final_amount'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }
}
