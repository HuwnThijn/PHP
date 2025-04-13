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
    
    // Các trạng thái lịch hẹn
    const STATUS_PENDING = 'pending';     // Chờ xác nhận
    const STATUS_CONFIRMED = 'confirmed'; // Đã xác nhận
    const STATUS_COMPLETED = 'completed'; // Đã hoàn thành
    const STATUS_CANCELLED = 'cancelled'; // Đã hủy
    
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
    
    /**
     * Lấy tên trạng thái hiển thị
     */
    public function getStatusNameAttribute()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 'Chờ xác nhận';
            case self::STATUS_CONFIRMED:
                return 'Đã xác nhận';
            case self::STATUS_COMPLETED:
                return 'Đã hoàn thành';
            case self::STATUS_CANCELLED:
                return 'Đã hủy';
            default:
                return 'Không xác định';
        }
    }
    
    /**
     * Lấy màu badge cho trạng thái
     */
    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 'warning';
            case self::STATUS_CONFIRMED:
                return 'primary';
            case self::STATUS_COMPLETED:
                return 'success';
            case self::STATUS_CANCELLED:
                return 'danger';
            default:
                return 'secondary';
        }
    }
}
