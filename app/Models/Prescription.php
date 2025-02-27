<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prescription extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_prescription';
    protected $fillable = ['id_medical_record', 'medicine', 'dosage', 'frequency', 'duration', 'prescribed_at'];
    
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'id_medical_record');
    }
}
