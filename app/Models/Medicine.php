<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicine extends Model
{
    use HasFactory, SoftDeletes;

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
        'expiry_date' => 'date',
        'stock_quantity' => 'integer'
    ];

    // Accessor để format giá
    public function getPriceFormattedAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' VNĐ';
    }

    // Accessor để format ngày hết hạn
    public function getExpiryDateFormattedAttribute()
    {
        return $this->expiry_date->format('d/m/Y');
    }
} 