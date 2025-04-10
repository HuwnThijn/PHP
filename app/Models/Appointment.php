<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_appointment';
    
    protected $fillable = [
        'id_patient', 
        'id_doctor', 
        'id_service',
        'guest_name',
        'guest_email',
        'guest_phone',
        'appointment_time', 
        'status', 
        'notes'
    ];
    
    public function patient()
    {
        return $this->belongsTo(User::class, 'id_patient');
    }
    
    public function doctor()
    {
        return $this->belongsTo(User::class, 'id_doctor');
    }
    
    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service');
    }
    
    // Kiểm tra xem lịch hẹn có phải của khách vãng lai không
    public function isGuest()
    {
        return $this->id_patient === null && !empty($this->guest_name);
    }
}
