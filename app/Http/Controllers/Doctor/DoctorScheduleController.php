<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DoctorScheduleController extends Controller
{
    /**
     * Middleware để đảm bảo chỉ bác sĩ đã đăng nhập mới được truy cập
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['getDoctorsAvailableOn']);
        $this->middleware('doctor')->except(['getDoctorsAvailableOn']);
    }

    /**
     * Hiển thị trang quản lý lịch làm việc của bác sĩ
     */
    public function index()
    {
        $doctorId = Auth::id();
        $currentWeekStart = Carbon::now()->startOfWeek();
        
        // Lấy lịch làm việc của tuần hiện tại
        $schedules = Schedule::getWeeklyScheduleForDoctor($doctorId);
        
        // Tạo mảng dữ liệu cho 7 ngày trong tuần
        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $day = (clone $currentWeekStart)->addDays($i);
            $weekDays[$i] = [
                'date' => $day->format('Y-m-d'),
                'day_name' => $day->format('l'),
                'formatted_date' => $day->format('d/m/Y')
            ];
        }
        
        return view('doctor.schedules.index', compact('schedules', 'weekDays', 'currentWeekStart'));
    }
    
    /**
     * Hiển thị lịch làm việc của một tuần cụ thể
     */
    public function showWeek(Request $request)
    {
        $doctorId = Auth::id();
        $weekStart = Carbon::parse($request->week_start ?? Carbon::now()->startOfWeek());
        
        // Lấy lịch làm việc của tuần được chọn
        $schedules = Schedule::getWeeklyScheduleForDoctor($doctorId, $weekStart);
        
        // Tạo mảng dữ liệu cho 7 ngày trong tuần
        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $day = (clone $weekStart)->addDays($i);
            $weekDays[$i] = [
                'date' => $day->format('Y-m-d'),
                'day_name' => $day->format('l'),
                'formatted_date' => $day->format('d/m/Y')
            ];
        }
        
        // Lấy tuần trước và tuần sau
        $prevWeek = (clone $weekStart)->subWeek()->format('Y-m-d');
        $nextWeek = (clone $weekStart)->addWeek()->format('Y-m-d');
        
        return view('doctor.schedules.index', compact('schedules', 'weekDays', 'weekStart', 'prevWeek', 'nextWeek'));
    }
    
    /**
     * Hiển thị form thêm lịch làm việc mới
     */
    public function create()
    {
        return view('doctor.schedules.create');
    }
    
    /**
     * Lưu lịch làm việc mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'repeat_weekly' => 'boolean',
            'notes' => 'nullable|string|max:500'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Kiểm tra xem có trùng lặp lịch không
        $existingSchedule = Schedule::where('doctor_id', Auth::id())
            ->where('date', $request->date)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })
            ->first();
            
        if ($existingSchedule) {
            return redirect()->back()
                ->with('error', 'Đã có lịch làm việc trong khoảng thời gian này.')
                ->withInput();
        }
        
        // Tạo lịch làm việc mới
        $schedule = new Schedule();
        $schedule->doctor_id = Auth::id();
        $schedule->date = $request->date;
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->is_available = true;
        $schedule->repeat_weekly = $request->has('repeat_weekly');
        $schedule->notes = $request->notes;
        $schedule->save();
        
        return redirect()->route('doctor.schedules.index')
            ->with('success', 'Đã thêm lịch làm việc thành công.');
    }
    
    /**
     * Hiển thị form chỉnh sửa lịch làm việc
     */
    public function edit($id)
    {
        $schedule = Schedule::where('id', $id)
            ->where('doctor_id', Auth::id())
            ->firstOrFail();
            
        return view('doctor.schedules.edit', compact('schedule'));
    }
    
    /**
     * Cập nhật lịch làm việc
     */
    public function update(Request $request, $id)
    {
        $schedule = Schedule::where('id', $id)
            ->where('doctor_id', Auth::id())
            ->firstOrFail();
            
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'is_available' => 'boolean',
            'repeat_weekly' => 'boolean',
            'notes' => 'nullable|string|max:500'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Kiểm tra xem có trùng lặp lịch không (ngoại trừ lịch hiện tại)
        $existingSchedule = Schedule::where('doctor_id', Auth::id())
            ->where('date', $request->date)
            ->where('id', '!=', $id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })
            ->first();
            
        if ($existingSchedule) {
            return redirect()->back()
                ->with('error', 'Đã có lịch làm việc khác trong khoảng thời gian này.')
                ->withInput();
        }
        
        // Cập nhật lịch
        $schedule->date = $request->date;
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->is_available = $request->has('is_available');
        $schedule->repeat_weekly = $request->has('repeat_weekly');
        $schedule->notes = $request->notes;
        $schedule->save();
        
        return redirect()->route('doctor.schedules.index')
            ->with('success', 'Đã cập nhật lịch làm việc thành công.');
    }
    
    /**
     * Xóa lịch làm việc
     */
    public function destroy($id)
    {
        $schedule = Schedule::where('id', $id)
            ->where('doctor_id', Auth::id())
            ->firstOrFail();
            
        $schedule->delete();
        
        return redirect()->route('doctor.schedules.index')
            ->with('success', 'Đã xóa lịch làm việc thành công.');
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
}
