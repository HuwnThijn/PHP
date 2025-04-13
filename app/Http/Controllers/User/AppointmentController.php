<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    /**
     * Hiển thị form đặt lịch
     */
    public function create()
    {
        $doctors = User::where('id_role', 2)
                       ->where('status', User::STATUS_ACTIVE)
                       ->get();
        
        // Mặc định hiển thị danh sách bác sĩ có lịch làm việc vào ngày hiện tại
        $availableDoctors = Schedule::getDoctorsAvailableOn(Carbon::today());
        
        return view('user.appointments.create', compact('doctors', 'availableDoctors'));
    }
    
    /**
     * Lưu lịch hẹn
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:users,id_user',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason' => 'required|string|max:500',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
        }
        
        // Kiểm tra xem bác sĩ có lịch làm việc vào ngày và giờ này không
        $appointmentDateTime = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);
        $dayOfWeek = $appointmentDateTime->dayOfWeek;
        $timeOnly = $appointmentDateTime->format('H:i:s');
        
        $doctorAvailable = Schedule::where('doctor_id', $request->doctor_id)
            ->where('is_available', true)
            ->where(function($query) use ($request, $dayOfWeek, $timeOnly) {
                $query->where(function($q) use ($request, $timeOnly) {
                    $q->where('date', $request->appointment_date)
                      ->where('start_time', '<=', $timeOnly)
                      ->where('end_time', '>=', $timeOnly);
                })
                ->orWhere(function($q) use ($dayOfWeek, $timeOnly) {
                    $q->where('repeat_weekly', true)
                      ->whereRaw("DAYOFWEEK(date) = ?", [$dayOfWeek + 1])
                      ->where('start_time', '<=', $timeOnly)
                      ->where('end_time', '>=', $timeOnly);
                });
            })
            ->exists();
            
        if (!$doctorAvailable) {
            return redirect()->back()
                    ->with('error', 'Bác sĩ không có lịch làm việc vào thời gian này.')
                    ->withInput();
        }
        
        // Kiểm tra xem bác sĩ có lịch hẹn khác trong cùng thời gian không
        $existingAppointment = Appointment::where('id_doctor', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->exists();
            
        if ($existingAppointment) {
            return redirect()->back()
                    ->with('error', 'Bác sĩ đã có lịch hẹn khác vào thời gian này. Vui lòng chọn thời gian khác.')
                    ->withInput();
        }
        
        // Tạo lịch hẹn mới
        $appointment = new Appointment();
        $appointment->id_patient = Auth::id();
        $appointment->id_doctor = $request->doctor_id;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->appointment_time = $request->appointment_time;
        $appointment->reason = $request->reason;
        $appointment->status = Appointment::STATUS_PENDING;
        $appointment->save();
        
        return redirect()->route('user.appointments.index')
                ->with('success', 'Đặt lịch hẹn thành công. Vui lòng đợi bác sĩ xác nhận.');
    }
    
    /**
     * Hiển thị lịch hẹn của người dùng
     */
    public function index()
    {
        $appointments = Appointment::where('id_patient', Auth::id())
                                  ->orderBy('appointment_date', 'desc')
                                  ->orderBy('appointment_time', 'desc')
                                  ->get();
                                  
        return view('user.appointments.index', compact('appointments'));
    }
    
    /**
     * API để lấy danh sách bác sĩ có lịch làm việc vào một ngày cụ thể
     */
    public function getDoctorsAvailableOn(Request $request)
    {
        $date = $request->date ?? Carbon::now()->format('Y-m-d');
        
        $doctors = Schedule::getDoctorsAvailableOn($date);
        
        return response()->json([
            'success' => true,
            'doctors' => $doctors
        ]);
    }
    
    /**
     * API để lấy khung giờ làm việc của một bác sĩ vào một ngày cụ thể
     */
    public function getDoctorAvailableTimeSlots(Request $request)
    {
        $doctorId = $request->doctor_id;
        $date = $request->date ?? Carbon::now()->format('Y-m-d');
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        
        // Lấy lịch làm việc của bác sĩ trong ngày
        $schedules = Schedule::where('doctor_id', $doctorId)
            ->where('is_available', true)
            ->where(function($query) use ($date, $dayOfWeek) {
                $query->where('date', $date)
                      ->orWhere(function($q) use ($dayOfWeek) {
                          $q->where('repeat_weekly', true)
                            ->whereRaw("DAYOFWEEK(date) = ?", [$dayOfWeek + 1]);
                      });
            })
            ->get();
            
        // Lấy các lịch hẹn đã được đặt
        $bookedAppointments = Appointment::where('id_doctor', $doctorId)
            ->where('appointment_date', $date)
            ->whereIn('status', [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED])
            ->pluck('appointment_time')
            ->toArray();
            
        // Tạo các khung giờ làm việc với khoảng cách 30 phút
        $timeSlots = [];
        foreach ($schedules as $schedule) {
            $startTime = Carbon::parse($schedule->start_time);
            $endTime = Carbon::parse($schedule->end_time);
            
            while ($startTime < $endTime) {
                $timeSlot = $startTime->format('H:i');
                
                // Kiểm tra xem khung giờ này đã được đặt chưa
                if (!in_array($timeSlot, $bookedAppointments)) {
                    $timeSlots[] = [
                        'time' => $timeSlot,
                        'formatted_time' => $startTime->format('H:i')
                    ];
                }
                
                $startTime->addMinutes(30);
            }
        }
        
        return response()->json([
            'success' => true,
            'time_slots' => $timeSlots
        ]);
    }

    /**
     * Lấy danh sách bác sĩ có lịch trống theo ngày
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoctorsAvailable(Request $request)
    {
        try {
            $date = $request->input('date');
            
            if (!$date) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng chọn ngày'
                ]);
            }
            
            // Sử dụng phương thức từ model Schedule để lấy danh sách bác sĩ có lịch trống
            $doctors = \App\Models\Schedule::getDoctorsAvailableOn($date);
            
            return response()->json([
                'success' => true,
                'doctors' => $doctors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
            ]);
        }
    }
} 