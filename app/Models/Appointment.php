<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_appointment';
    protected $fillable = ['id_patient', 'id_doctor', 'appointment_time', 'status', 'notes'];
    
    public function patient()
    {
        return $this->belongsTo(User::class, 'id_patient');
    }
    
    public function doctor()
    {
        return $this->belongsTo(User::class, 'id_doctor');
    }
}
