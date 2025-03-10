<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'manufacturer',
        'expiry_date',
        'dosage_form', // viên, siro, tiêm...
        'usage_instructions'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'expiry_date' => 'date'
    ];
} 