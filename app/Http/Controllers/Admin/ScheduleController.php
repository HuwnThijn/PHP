<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Hiển thị trang quản lý lịch làm việc
     */
    public function index()
    {
        $doctors = User::where('id_role', 2)->where('status', User::STATUS_ACTIVE)->get();
        return view('admin.schedules.index', compact('doctors'));
    }

    /**
     * Hiển thị lịch làm việc của một bác sĩ
     */
    public function doctorSchedule(Request $request, $doctorId)
    {
        $doctor = User::where('id_user', $doctorId)->where('id_role', 2)->firstOrFail();
        
        $weekStart = Carbon::parse($request->week_start ?? Carbon::now()->startOfWeek());
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
        
        return view('admin.schedules.doctor', compact('doctor', 'schedules', 'weekDays', 'weekStart', 'prevWeek', 'nextWeek'));
    }
    
    /**
     * Hiển thị form thêm lịch làm việc mới
     */
    public function create($doctorId)
    {
        $doctor = User::where('id_user', $doctorId)->where('id_role', 2)->firstOrFail();
        return view('admin.schedules.create', compact('doctor'));
    }
    
    /**
     * Lưu lịch làm việc mới
     */
    public function store(Request $request, $doctorId)
    {
        $doctor = User::where('id_user', $doctorId)->where('id_role', 2)->firstOrFail();
        
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
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
        $existingSchedule = Schedule::where('doctor_id', $doctorId)
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
        $schedule->doctor_id = $doctorId;
        $schedule->date = $request->date;
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->is_available = true;
        $schedule->repeat_weekly = $request->has('repeat_weekly');
        $schedule->notes = $request->notes;
        $schedule->save();
        
        return redirect()->route('admin.schedules.doctor', $doctorId)
            ->with('success', 'Đã thêm lịch làm việc thành công.');
    }
    
    /**
     * Hiển thị form chỉnh sửa lịch làm việc
     */
    public function edit($doctorId, $id)
    {
        $doctor = User::where('id_user', $doctorId)->where('id_role', 2)->firstOrFail();
        $schedule = Schedule::where('id', $id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();
            
        return view('admin.schedules.edit', compact('doctor', 'schedule'));
    }
    
    /**
     * Cập nhật lịch làm việc
     */
    public function update(Request $request, $doctorId, $id)
    {
        $doctor = User::where('id_user', $doctorId)->where('id_role', 2)->firstOrFail();
        $schedule = Schedule::where('id', $id)
            ->where('doctor_id', $doctorId)
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
        $existingSchedule = Schedule::where('doctor_id', $doctorId)
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
        
        return redirect()->route('admin.schedules.doctor', $doctorId)
            ->with('success', 'Đã cập nhật lịch làm việc thành công.');
    }
    
    /**
     * Xóa lịch làm việc
     */
    public function destroy($doctorId, $id)
    {
        $schedule = Schedule::where('id', $id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();
            
        $schedule->delete();
        
        return redirect()->route('admin.schedules.doctor', $doctorId)
            ->with('success', 'Đã xóa lịch làm việc thành công.');
    }
}
