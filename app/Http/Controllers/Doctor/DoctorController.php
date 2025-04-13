<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Medicine;
use App\Models\Treatment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['showLoginForm', 'login']);
        $this->middleware('doctor')->except(['showLoginForm', 'login']);
    }

    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->id_role === 2) {
            return redirect()->route('doctor.dashboard');
        }
        return view('doctor.auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            // Kiểm tra vai trò
            if (Auth::user()->id_role == 2) {
                $request->session()->regenerate();
                return redirect()->route('doctor.dashboard');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tài khoản này không có quyền truy cập vào hệ thống bác sĩ.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ]);
    }

    /**
     * Đăng xuất khỏi hệ thống
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('doctor.login');
    }

    /**
     * Hiển thị trang dashboard
     */
    public function dashboard()
    {
        // Lấy số lượng bệnh nhân đã khám trong ngày
        $patientsToday = MedicalRecord::where('id_doctor', Auth::id())
            ->whereDate('created_at', Carbon::today())
            ->count();
        
        // Lấy số lượng bệnh nhân đang chờ khám
        $pendingPatients = MedicalRecord::where('id_doctor', Auth::id())
            ->whereNull('diagnosis')
            ->count();
        
        // Lấy danh sách bệnh nhân mới nhất cần khám
        $pendingMedicalRecords = MedicalRecord::with('patient')
            ->where('id_doctor', Auth::id())
            ->whereNull('diagnosis')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Lấy danh sách bệnh nhân đã khám gần đây
        $recentMedicalRecords = MedicalRecord::with('patient')
            ->where('id_doctor', Auth::id())
            ->whereNotNull('diagnosis')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('doctor.dashboard', compact(
            'patientsToday', 
            'pendingPatients', 
            'pendingMedicalRecords', 
            'recentMedicalRecords'
        ));
    }

    /**
     * Hiển thị danh sách bệnh nhân đang chờ khám
     */
    public function pendingPatients()
    {
        $patients = MedicalRecord::with('patient')
            ->where('id_doctor', Auth::id())
            ->whereNull('diagnosis')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('doctor.patients.pending', compact('patients'));
    }

    /**
     * Hiển thị danh sách bệnh nhân đã khám
     */
    public function patientHistory()
    {
        $patients = MedicalRecord::with('patient')
            ->where('id_doctor', Auth::id())
            ->whereNotNull('diagnosis')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('doctor.patients.history', compact('patients'));
    }

    /**
     * Hiển thị form khám bệnh và kê đơn
     */
    public function showExamination($id)
    {
        $medicalRecord = MedicalRecord::with('patient')
            ->where('id_doctor', Auth::id())
            ->findOrFail($id);
        
        // Lấy danh sách thuốc
        $medicines = Medicine::where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get();
        
        // Lấy danh sách liệu trình điều trị
        $treatments = Treatment::orderBy('name')->get();
        
        return view('doctor.patients.examination', compact('medicalRecord', 'medicines', 'treatments'));
    }

    /**
     * Lưu thông tin khám bệnh và đơn thuốc
     */
    public function saveExamination(Request $request, $id)
    {
        $request->validate([
            'diagnosis' => 'required|string',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.dosage' => 'required|string',
            'items.*.instructions' => 'required|string',
            'treatments' => 'nullable|array',
            'treatments.*' => 'exists:treatments,id'
        ]);

        DB::beginTransaction();

        try {
            // Cập nhật hồ sơ bệnh án
            $medicalRecord = MedicalRecord::where('id_doctor', Auth::id())
                ->findOrFail($id);
            
            $medicalRecord->diagnosis = $request->diagnosis;
            $medicalRecord->notes = $request->notes;
            $medicalRecord->save();
            
            // Tạo đơn thuốc mới
            $prescription = Prescription::create([
                'id_medical_record' => $medicalRecord->id_medical_record,
                'id_patient' => $medicalRecord->id_patient,
                'id_doctor' => Auth::id(),
                'diagnosis' => $request->diagnosis,
                'notes' => $request->notes,
                'status' => 'pending'
            ]);
            
            // Thêm thuốc vào đơn
            if ($request->has('items')) {
                foreach ($request->items as $item) {
                    $medicine = Medicine::findOrFail($item['medicine_id']);
                    
                    PrescriptionItem::create([
                        'prescription_id' => $prescription->id_prescription,
                        'medicine_id' => $medicine->id,
                        'quantity' => $item['quantity'],
                        'dosage' => $item['dosage'],
                        'instructions' => $item['instructions'],
                        'price' => $medicine->price
                    ]);
                }
            }
            
            // Thêm liệu trình điều trị (nếu có)
            if ($request->has('treatments')) {
                foreach ($request->treatments as $treatmentId) {
                    $treatment = Treatment::findOrFail($treatmentId);
                    
                    // Bạn có thể thêm liệu trình vào bản ghi khác nếu cần
                    // Ví dụ: $prescription->treatments()->attach($treatmentId);
                }
            }
            
            DB::commit();
            
            return redirect()->route('doctor.patients.pending')
                ->with('success', 'Đã hoàn thành khám bệnh và kê đơn thuốc thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hiển thị thông tin chi tiết một lần khám
     */
    public function showMedicalRecord($id)
    {
        $medicalRecord = MedicalRecord::with(['patient', 'prescriptions.items.medicine'])
            ->where('id_doctor', Auth::id())
            ->findOrFail($id);
        
        return view('doctor.patients.show', compact('medicalRecord'));
    }

    /**
     * Hiển thị danh sách đơn thuốc
     */
    public function prescriptionIndex()
    {
        $prescriptions = Prescription::with(['patient', 'doctor'])
            ->where('id_doctor', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('doctor.prescriptions.index', compact('prescriptions'));
    }
    
    /**
     * Hiển thị chi tiết đơn thuốc
     */
    public function showPrescription($id)
    {
        $prescription = Prescription::with(['patient', 'doctor', 'items.medicine', 'processedBy'])
            ->where('id_doctor', Auth::id())
            ->findOrFail($id);
        
        return view('doctor.prescriptions.show', compact('prescription'));
    }
} 