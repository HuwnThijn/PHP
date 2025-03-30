<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prescription extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_prescription';
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'diagnosis',
        'notes',
        'total_amount',
        'status',
        'processed_by',
        'processed_at'
    ];
    
    protected $casts = [
        'processed_at' => 'datetime',
        'total_amount' => 'decimal:2'
    ];
    
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id', 'id_user');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'id_user');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by', 'id_user');
    }

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }
}
