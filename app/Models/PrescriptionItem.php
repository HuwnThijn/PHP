<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'medicine_id',
        'quantity',
        'dosage',
        'instructions',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'prescription_id', 'id_prescription');
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id', 'id');
    }
} 