<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Medicine;
use App\Models\Treatment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['showLoginForm', 'login']);
        $this->middleware('admin')->except(['showLoginForm', 'login']);
    }

    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->id_role === 1) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Kiểm tra user có tồn tại không
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email không tồn tại trong hệ thống.',
            ])->withInput($request->except('password'));
        }

        // Debug: In ra thông tin để kiểm tra
        Log::info('Login attempt', [
            'email' => $credentials['email'],
            'user_exists' => !!$user,
            'id_role' => $user->id_role,
            'password_hash' => $user->password,
            'password_check' => Hash::check($credentials['password'], $user->password)
        ]);

        // Kiểm tra mật khẩu
        if (!Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'password' => 'Mật khẩu không chính xác.',
            ])->withInput($request->except('password'));
        }

        // Kiểm tra role
        if ($user->id_role !== 1) {
            return back()->withErrors([
                'email' => 'Tài khoản không có quyền truy cập trang quản trị.',
            ])->withInput($request->except('password'));
        }

        // Đăng nhập
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login.form');
    }

    public function dashboard()
    {
        // Đếm số lượng bác sĩ, dược sĩ, thuốc và trị liệu
        $doctorCount = User::where('id_role', 2)->count();
        $pharmacistCount = User::where('id_role', 3)->count();
        $medicineCount = Medicine::count();
        $treatmentCount = Treatment::count();

        // Dữ liệu cho biểu đồ doanh thu
        $revenueData = [
            'labels' => ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
            'data' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0] // Sẽ được cập nhật từ database
        ];

        // Dữ liệu phân bố doanh thu
        $revenueDistribution = [
            'medicine' => 0,
            'treatment' => 0,
            'other' => 0
        ];

        return view('admin.dashboard', compact(
            'doctorCount',
            'pharmacistCount',
            'medicineCount',
            'treatmentCount',
            'revenueData',
            'revenueDistribution'
        ));
    }

    // Quản lý user (bác sĩ, dược sĩ)
    public function createMedicalStaff(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:2,3', // 2: bác sĩ, 3: dược sĩ
            'phone' => 'required|string',
            'address' => 'required|string',
            'specialization' => 'required_if:role,2|string|nullable'
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'id_role' => $validated['role'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'specialization' => $validated['specialization'] ?? null,
                'status' => 'active'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tạo tài khoản thành công',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi tạo tài khoản: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo tài khoản: ' . $e->getMessage()
            ], 500);
        }
    }

    // Xử lý tài khoản bị khóa
    public function toggleUserLock($userId)
    {
        $user = User::findOrFail($userId);
        $user->is_locked = !$user->is_locked;
        $user->save();

        $status = $user->is_locked ? 'khóa' : 'mở khóa';
        return response()->json(['message' => "Đã $status tài khoản"]);
    }

    // Xem doanh thu
    public function getRevenue(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now());

        // Logic tính doanh thu ở đây
        $revenue = [
            'total' => 0,
            'treatments' => 0,
            'medicines' => 0,
            'details' => []
        ];

        return response()->json($revenue);
    }

    // Quản lý thuốc
    public function createMedicine(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'manufacturer' => 'required|string',
            'expiry_date' => 'required|date',
            'dosage_form' => 'required|string',
            'usage_instructions' => 'required|string'
        ]);

        $medicine = Medicine::create($validated);
        return response()->json(['message' => 'Thêm thuốc thành công', 'medicine' => $medicine]);
    }

    // Quản lý trị liệu
    public function createTreatment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'equipment_needed' => 'required|string',
            'contraindications' => 'required|string',
            'side_effects' => 'required|string'
        ]);

        $treatment = Treatment::create($validated);
        return response()->json(['message' => 'Thêm phương pháp trị liệu thành công', 'treatment' => $treatment]);
    }

    public function medicineIndex()
    {
        $medicines = Medicine::orderBy('name')
            ->paginate(10);
        return view('admin.medicine.index', compact('medicines'));
    }

    public function treatmentIndex()
    {
        $treatments = Treatment::orderBy('name')
            ->paginate(10);
        return view('admin.treatment.index', compact('treatments'));
    }

    public function revenueIndex()
    {
        // Lấy dữ liệu doanh thu tháng hiện tại
        $currentMonth = Carbon::now();
        $startDate = $currentMonth->startOfMonth();
        $endDate = $currentMonth->copy()->endOfMonth();

        // Tổng doanh thu từ thuốc
        $medicineSales = Medicine::whereBetween('created_at', [$startDate, $endDate])
            ->sum('price');

        // Tổng doanh thu từ trị liệu
        $treatmentSales = Treatment::whereBetween('created_at', [$startDate, $endDate])
            ->sum('price');

        // Tổng doanh thu
        $totalRevenue = $medicineSales + $treatmentSales;

        // Dữ liệu cho biểu đồ
        $revenueData = [
            'labels' => ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
            'data' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0] // Sẽ được cập nhật từ database
        ];

        // Phân bố doanh thu
        $revenueDistribution = [
            'medicine' => $medicineSales,
            'treatment' => $treatmentSales,
            'other' => 0
        ];

        // Dữ liệu chi tiết theo ngày
        $revenue = [
            'total' => $totalRevenue,
            'medicines' => $medicineSales,
            'treatments' => $treatmentSales,
            'details' => []
        ];

        // Lấy chi tiết doanh thu theo ngày
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dayMedicineSales = Medicine::whereDate('created_at', $currentDate)
                ->sum('price');
            $dayTreatmentSales = Treatment::whereDate('created_at', $currentDate)
                ->sum('price');

            $revenue['details'][] = [
                'date' => $currentDate->format('d/m/Y'),
                'type' => 'Tổng hợp',
                'description' => 'Doanh thu ngày',
                'quantity' => 1,
                'unit_price' => $dayMedicineSales + $dayTreatmentSales,
                'total' => $dayMedicineSales + $dayTreatmentSales
            ];

            $currentDate->addDay();
        }

        return view('admin.revenue.index', compact(
            'revenue',
            'totalRevenue',
            'medicineSales',
            'treatmentSales',
            'revenueData',
            'revenueDistribution'
        ));
    }

    public function getRevenueData(Request $request)
    {
        $startDate = Carbon::parse($request->get('start_date', Carbon::now()->startOfMonth()));
        $endDate = Carbon::parse($request->get('end_date', Carbon::now()->endOfMonth()));

        // Tổng doanh thu từ thuốc
        $medicineSales = Medicine::whereBetween('created_at', [$startDate, $endDate])
            ->sum('price');

        // Tổng doanh thu từ trị liệu
        $treatmentSales = Treatment::whereBetween('created_at', [$startDate, $endDate])
            ->sum('price');

        // Chi tiết doanh thu theo ngày
        $details = [];
        $chartLabels = [];
        $chartData = [];
        
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dayMedicineSales = Medicine::whereDate('created_at', $currentDate)
                ->sum('price');
            $dayTreatmentSales = Treatment::whereDate('created_at', $currentDate)
                ->sum('price');
            
            $dateFormatted = $currentDate->format('d/m/Y');
            $dailyTotal = $dayMedicineSales + $dayTreatmentSales;

            $details[] = [
                'date' => $dateFormatted,
                'medicine' => $dayMedicineSales,
                'treatment' => $dayTreatmentSales,
                'total' => $dailyTotal
            ];

            $chartLabels[] = $dateFormatted;
            $chartData[] = $dailyTotal;

            $currentDate->addDay();
        }

        return response()->json([
            'total' => $medicineSales + $treatmentSales,
            'medicines' => $medicineSales,
            'treatments' => $treatmentSales,
            'details' => $details,
            'chart' => [
                'labels' => $chartLabels,
                'data' => $chartData
            ]
        ]);
    }

    public function staffIndex()
    {
        // Lấy danh sách tất cả user trừ admin (id_role = 1)
        $users = User::where('id_role', '!=', 1)
            ->orderBy('id_role')
            ->orderBy('name')
            ->paginate(10);

        // Đếm số lượng theo từng loại
        $counts = [
            'doctor' => User::where('id_role', 2)->count(),
            'pharmacist' => User::where('id_role', 3)->count(),
            'member' => User::where('id_role', 4)->count()
        ];

        return view('admin.staff.index', compact('users', 'counts'));
    }

    public function updateUserStatus(Request $request, $userId)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:active,temporary_locked,permanent_locked'
            ]);

            $user = User::findOrFail($userId);
            $user->status = $validated['status'];
            $user->save();

            $messages = [
                'active' => 'Đã kích hoạt tài khoản',
                'temporary_locked' => 'Đã tạm khóa tài khoản (30 ngày)',
                'permanent_locked' => 'Đã khóa vĩnh viễn tài khoản'
            ];

            return response()->json([
                'success' => true,
                'message' => $messages[$validated['status']]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
