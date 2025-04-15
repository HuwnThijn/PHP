<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prescription extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_prescription';
    protected $fillable = [
        'id_medical_record',
        'id_patient',
        'id_doctor',
        'diagnosis',
        'notes',
        'total_amount',
        'status',
        'processed_by',
        'processed_at',
        'payment_method',
        'payment_id',
        'payment_status'
    ];
    
    protected $casts = [
        'processed_at' => 'datetime',
        'total_amount' => 'decimal:2'
    ];
    
    // Các trạng thái của đơn thuốc
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    
    public function patient()
    {
        return $this->belongsTo(User::class, 'id_patient', 'id_user');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'id_doctor', 'id_user');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by', 'id_user');
    }

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class, 'prescription_id', 'id_prescription');
    }
    
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'id_medical_record', 'id_medical_record');
    }
}
