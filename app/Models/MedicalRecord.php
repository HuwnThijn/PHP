<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalRecord extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_medical_record';
    
    protected $fillable = [
        'id_patient',
        'id_doctor',
        'diagnosis',
        'notes',
        'pdf_url'
    ];
    
    public function patient()
    {
        return $this->belongsTo(User::class, 'id_patient', 'id_user');
    }
    
    public function doctor()
    {
        return $this->belongsTo(User::class, 'id_doctor', 'id_user');
    }
    
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'id_medical_record');
    }
}
