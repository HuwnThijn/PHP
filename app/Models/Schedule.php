<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;
    
    protected $table = 'doctor_schedules';

    protected $fillable = [
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'is_available',
        'repeat_weekly',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_available' => 'boolean',
        'repeat_weekly' => 'boolean'
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'id_user');
    }

    // Lấy danh sách bác sĩ có lịch làm việc vào một ngày cụ thể
    public static function getDoctorsAvailableOn($date)
    {
        $date = Carbon::parse($date)->format('Y-m-d');
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;

        // Lấy ID của các bác sĩ có lịch làm trong ngày này hoặc có lịch làm hàng tuần vào thứ này
        $doctorIds = self::where(function($query) use ($date, $dayOfWeek) {
                $query->where('date', $date)
                      ->where('is_available', true);
            })
            ->orWhere(function($query) use ($date, $dayOfWeek) {
                $query->where('repeat_weekly', true)
                      ->where('is_available', true)
                      ->whereRaw("DAYOFWEEK(date) = ?", [$dayOfWeek + 1]); // MySQL DAYOFWEEK bắt đầu từ 1 = Chủ nhật
            })
            ->pluck('doctor_id')
            ->unique();

        // Lấy thông tin các bác sĩ
        return User::whereIn('id_user', $doctorIds)
                  ->where('id_role', 2) // Vai trò bác sĩ
                  ->where('status', 'active')
                  ->get();
    }

    // Lấy lịch làm việc của một bác sĩ trong tuần
    public static function getWeeklyScheduleForDoctor($doctorId, $startDate = null)
    {
        if (!$startDate) {
            $startDate = Carbon::now()->startOfWeek();
        } else {
            $startDate = Carbon::parse($startDate)->startOfWeek();
        }
        
        $endDate = (clone $startDate)->endOfWeek();
        
        return self::where('doctor_id', $doctorId)
                 ->where(function($query) use ($startDate, $endDate) {
                     $query->whereBetween('date', [$startDate, $endDate])
                           ->orWhere('repeat_weekly', true);
                 })
                 ->orderBy('date')
                 ->orderBy('start_time')
                 ->get();
    }
} 