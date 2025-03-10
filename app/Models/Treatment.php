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
} 