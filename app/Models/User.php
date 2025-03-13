<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $primaryKey = 'id_user';
    
    protected $fillable = [
        'id_role',
        'id_rank',
        'name',
        'email',
        'password',
        'phone',
        'address',
        'specialization',
        'status'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'string'
    ];

    // Các hằng số cho trạng thái
    const STATUS_ACTIVE = 'active';
    const STATUS_TEMPORARY_LOCKED = 'temporary_locked';
    const STATUS_PERMANENT_LOCKED = 'permanent_locked';
    
    public function isAdmin()
    {
        return $this->id_role === 1;
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isTemporaryLocked()
    {
        return $this->status === self::STATUS_TEMPORARY_LOCKED;
    }

    public function isPermanentLocked()
    {
        return $this->status === self::STATUS_PERMANENT_LOCKED;
    }
    
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
