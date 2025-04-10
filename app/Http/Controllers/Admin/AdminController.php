<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Medicine;
use App\Models\Treatment;
use Illuminate\Support\Facades\Validator;
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
        try {
            // Validate dữ liệu
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'phone' => 'required|string',
                'address' => 'required|string',
                'role' => 'required|in:2,3',
            ]);

            // Tạo user mới
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'id_role' => $validated['role'],
                'id_rank' => 1,
                'status' => 1  // 1 = active, 0 = inactive
            ]);

            return redirect()->back()->with('success', 'Thêm nhân viên thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
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
    public function medicineIndex()
    {
        $medicines = Medicine::paginate(10);
        return view('admin.medicine.index', compact('medicines'));
    }

    public function showMedicine($id)
    {
        try {
            $medicine = Medicine::findOrFail($id);
            return response()->json($medicine);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không tìm thấy thuốc'], 404);
        }
    }

    public function medicineStore(Request $request)
    {
        try {
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

            Medicine::create($validated);

            return response()->json(['success' => true, 'message' => 'Thêm thuốc mới thành công!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xác thực: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateMedicine(Request $request, $id)
    {
        try {
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

            $medicine = Medicine::findOrFail($id);
            $medicine->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin thuốc thành công!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
             return response()->json([
                'success' => false,
                'message' => 'Lỗi xác thực: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteMedicine($id)
    {
        try {
            $medicine = Medicine::findOrFail($id);
            $medicine->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa thuốc thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    // Quản lý trị liệu
    public function treatmentIndex()
    {
        $treatments = Treatment::paginate(10);
        return view('admin.treatment.index', compact('treatments'));
    }

    public function showTreatment($id)
    {
        try {
            $treatment = Treatment::findOrFail($id);
            return response()->json($treatment);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Không tìm thấy phương pháp điều trị'], 404);
        }
    }

    public function treatmentStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'duration' => 'required|integer|min:1',
                'equipment_needed' => 'required|string',
                'contraindications' => 'required|string',
                'side_effects' => 'required|string'
            ]);

            Treatment::create($validated);

            return response()->json(['success' => true, 'message' => 'Thêm trị liệu mới thành công!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
             return response()->json([
                'success' => false,
                'message' => 'Lỗi xác thực: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateTreatment(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'duration' => 'required|integer|min:1',
                'equipment_needed' => 'required|string',
                'contraindications' => 'required|string',
                'side_effects' => 'required|string'
            ]);

            $treatment = Treatment::findOrFail($id);
            $treatment->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin trị liệu thành công!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
             return response()->json([
                'success' => false,
                'message' => 'Lỗi xác thực: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteTreatment($id)
    {
        try {
            $treatment = Treatment::findOrFail($id);
            $treatment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa trị liệu thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
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
        
        // Tạo collection cho doanh thu ngày
        $dailyRevenue = collect([
            (object)[
                'date' => Carbon::today()->format('Y-m-d'),
                'total' => Medicine::whereDate('created_at', Carbon::today())->sum('price') + 
                          Treatment::whereDate('created_at', Carbon::today())->sum('price'),
                'transaction_count' => Medicine::whereDate('created_at', Carbon::today())->count() +
                                      Treatment::whereDate('created_at', Carbon::today())->count()
            ]
        ]);
        
        // Tạo collection cho doanh thu tháng
        $monthlyRevenue = collect([
            (object)[
                'year' => Carbon::now()->year,
                'month' => Carbon::now()->month,
                'total' => $totalRevenue
            ]
        ]);

        return view('admin.revenue.index', compact(
            'revenue',
            'totalRevenue',
            'medicineSales',
            'treatmentSales',
            'revenueData',
            'revenueDistribution',
            'dailyRevenue',
            'monthlyRevenue'
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

    // Quản lý nhân viên
    public function staffIndex()
    {
        $doctors = User::where('id_role', 2)->get();
        $pharmacists = User::where('id_role', 3)->get();
        
        // Tính doanh thu ngày
        $dailyMedicineSales = Medicine::whereDate('created_at', Carbon::today())->sum('price');
        $dailyTreatmentSales = Treatment::whereDate('created_at', Carbon::today())->sum('price');
        $dailyRevenue = $dailyMedicineSales + $dailyTreatmentSales;
        
        // Tính doanh thu tuần
        $weeklyMedicineSales = Medicine::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('price');
        $weeklyTreatmentSales = Treatment::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('price');
        $weeklyRevenue = $weeklyMedicineSales + $weeklyTreatmentSales;
        
        // Tính doanh thu tháng
        $monthlyMedicineSales = Medicine::whereMonth('created_at', Carbon::now()->month)
                                        ->whereYear('created_at', Carbon::now()->year)
                                        ->sum('price');
        $monthlyTreatmentSales = Treatment::whereMonth('created_at', Carbon::now()->month)
                                          ->whereYear('created_at', Carbon::now()->year)
                                          ->sum('price');
        $monthlyRevenue = $monthlyMedicineSales + $monthlyTreatmentSales;
        
        return view('admin.staff.index', compact(
            'doctors', 
            'pharmacists',
            'dailyRevenue',
            'weeklyRevenue',
            'monthlyRevenue'
        ));
    }

    public function updateUserStatus(Request $request, $userId)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:0,1'
            ]);

            $user = User::findOrFail($userId);
            $user->status = $validated['status'];
            $user->save();

            $statusText = $validated['status'] == 1 ? 'kích hoạt' : 'vô hiệu hóa';
            
            return response()->json([
                'success' => true,
                'message' => "Đã $statusText tài khoản thành công!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật thông tin nhân viên
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateStaff(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id . ',id_user',
                'phone' => 'required|string',
                'address' => 'required|string',
            ]);

            $user = User::findOrFail($id);
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin nhân viên thành công!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xác thực: ' . implode(', ', $e->validator->errors()->all())
            ], 422); // HTTP 422 Unprocessable Entity
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500); // HTTP 500 Internal Server Error
        }
    }

    // Quản lý khách hàng
    public function customerIndex()
    {
        $customers = User::where('id_role', 4)->paginate(10); // Role 4 là member
        $counts = [
            'member' => User::where('id_role', 4)->count(),
            'active' => User::where('id_role', 4)->where('status', 1)->count(),
            'inactive' => User::where('id_role', 4)->where('status', 0)->count(),
        ];
        
        return view('admin.customers.index', compact('customers', 'counts'));
    }

    public function updateCustomerStatus(Request $request, $userId)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:0,1'
            ]);

            $user = User::where('id_role', 4)->findOrFail($userId);
            $user->status = $validated['status'];
            $user->save();

            $statusText = $validated['status'] == 1 ? 'kích hoạt' : 'vô hiệu hóa';
            return response()->json([
                'success' => true,
                'message' => "Đã $statusText tài khoản khách hàng thành công!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    // Quản lý thành viên
    public function memberIndex()
    {
        $members = User::where('id_role', 4)->paginate(10); // Role 4 là member
        $counts = [
            'member' => User::where('id_role', 4)->count(),
        ];
        
        return view('admin.member.index', compact('members', 'counts'));
    }

    public function memberStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'phone' => 'required|string',
                'address' => 'required|string',
            ]);

            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'id_role' => 4, // Role member
                'id_rank' => 1,
                'status' => 1
            ]);

            return redirect()->back()->with('success', 'Thêm thành viên thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function memberUpdate(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id . ',id_user',
                'phone' => 'required|string',
                'address' => 'required|string',
            ]);

            $user = User::where('id_role', 4)->findOrFail($id);
            $user->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin thành viên thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin thành viên cho form chỉnh sửa
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getMemberInfo($id)
    {
        try {
            $member = User::where('id_role', 4)->findOrFail($id);
            return response()->json($member);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thành viên: ' . $e->getMessage()
            ], 404);
        }
    }

    public function updateMemberStatus(Request $request, $userId)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:active,inactive'
            ]);

            $user = User::where('id_role', 4)->findOrFail($userId);
            
            // Map giá trị 'inactive' từ request sang 'temporary_locked' trong database
            $status = $validated['status'] === 'inactive' ? 'temporary_locked' : $validated['status'];
            $user->status = $status;
            $user->save();

            $statusText = $validated['status'] == 'active' ? 'kích hoạt' : 'vô hiệu hóa';
            return response()->json([
                'success' => true,
                'message' => "Đã $statusText tài khoản thành viên thành công!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Quản lý trạng thái tài khoản người dùng
     * 
     * @param Request $request
     * @param \App\Models\User|int $user
     * @return \Illuminate\Http\Response
     */
    public function manageAccountStatus(Request $request, $user)
    {
        try {
            // Kiểm tra nếu $user không phải là instance của User, lấy user từ ID
            if (!($user instanceof \App\Models\User)) {
                $user = User::findOrFail($user);
            }
            
            $validated = $request->validate([
                'action' => 'required|in:unlock,delete',
                'status' => 'required|in:active,temporary_locked,permanent_locked'
            ]);
            
            if ($validated['action'] === 'unlock') {
                // Sử dụng trực tiếp giá trị status chuỗi
                $user->status = $validated['status'];
                $user->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Đã cập nhật trạng thái tài khoản thành công'
                ]);
            } else {
                $user->delete();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Đã xóa tài khoản thành công'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
