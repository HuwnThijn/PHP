<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Treatment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration', // thời gian điều trị (phút)
        'equipment_needed',
        'contraindications', // chống chỉ định
        'side_effects'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration' => 'integer'
    ];

    // Accessor để format giá
    public function getPriceFormattedAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' VNĐ';
    }

    // Accessor để format thời gian
    public function getDurationFormattedAttribute()
    {
        return $this->duration . ' phút';
    }
} 