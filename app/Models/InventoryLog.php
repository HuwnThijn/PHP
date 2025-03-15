<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'quantity',
        'type',
        'note',
        'user_id',
        'batch_number',
        'expiry_date',
        'supplier',
        'unit_price'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'unit_price' => 'decimal:2'
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
} 