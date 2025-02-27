<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;
    
    protected $primaryKey = 'id_user';
    protected $fillable = [
        'id_role', 'id_rank', 'name', 'email', 'phone', 'password',
        'age', 'gender', 'address', 'points', 'total_spent',
        'last_transaction', 'status', 'failed_appointments'
    ];
    
    protected $hidden = ['password'];
    
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }
    
    public function rank()
    {
        return $this->belongsTo(Rank::class, 'id_rank');
    }
    
    public function patientAppointments()
    {
        return $this->hasMany(Appointment::class, 'id_patient');
    }
    
    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'id_doctor');
    }
    
    public function patientMedicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'id_patient');
    }
    
    public function doctorMedicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'id_doctor');
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_user');
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_user');
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_user');
    }
}
