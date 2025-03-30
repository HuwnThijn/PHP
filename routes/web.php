<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Pharmacist\PharmacistController;
use App\Http\Controllers\Auth\PharmacistLoginController;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Routes người dùng
Route::get('/', [UserController::class,'index'])-> name('index');
Route::get('/product/{slug}', [UserController::class,'product'])-> name('product');
//Route::get('/doctor/{slug}', [UserController::class,'doctor'])-> name('doctor');
Route::get('/doctor/', [UserController::class,'doctor'])-> name('doctor');
Route::get('/detailsp/{slug}', [UserController::class,'detailsp'])-> name('detailsp');
Route::get('/about/', [UserController::class,'about'])-> name('about');
Route::get('/service/', [UserController::class,'service'])-> name('service');
Route::get('/contact/', [UserController::class,'contact'])-> name('contact');
Route::get('/store/', [UserController::class,'store'])-> name('store');
Route::get('/department/', [UserController::class,'department'])-> name('department');
Route::get('/department-single/', [UserController::class,'departmentSingle'])-> name('department-single');
Route::get('/appoinment/', [UserController::class,'appoinment'])-> name('appoinment');
Route::get('/doctor-single/{id?}', [UserController::class,'doctorSingle'])-> name('doctor-single');
Route::get('/confirmation/', [UserController::class,'confirmation'])-> name('confirmation');

// User Authentication Routes
Route::prefix('user')->name('user.')->group(function () {
    // Doctor single page route
    Route::get('/doctor-single/{id?}', [UserController::class, 'doctorSingle'])->name('doctorSingle');
    
    // Appointment route
    Route::get('/appointment', [UserController::class, 'appoinment'])->name('appointment');
    
    // Appointment route with original spelling to match template
    Route::get('/appoinment', [UserController::class, 'appoinment'])->name('appoinment');
    
    // Appointment store and confirmation routes
    Route::post('/appointment/store', [UserController::class, 'storeAppointment'])->name('appointment.store');
    Route::get('/appointment/confirmation', [UserController::class, 'appointmentConfirmation'])->name('appointment.confirmation');
    
    // Appointment history page
    Route::get('/appointment/history', [UserController::class, 'appointmentHistory'])->name('appointment.history');
    
    // Guest routes
    Route::middleware('guest')->group(function () {
        // Login
        Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [UserController::class, 'login'])->name('login.submit');
        
        // Register
        Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [UserController::class, 'register'])->name('register.submit');
        
        // Email Verification
        Route::get('/verify-email/{token}', [UserController::class, 'verifyEmail'])->name('verify.email');
        
        // Password Reset
        Route::get('/forgot-password', [UserController::class, 'showForgotPasswordForm'])->name('password.request');
        Route::post('/forgot-password', [UserController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('/reset-password/{token}', [UserController::class, 'showResetPasswordForm'])->name('password.reset');
        Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('password.update');
    });
    
    // Authenticated user routes
    Route::middleware('auth')->group(function () {
        // Logout
        Route::post('/logout', [UserController::class, 'logout'])->name('logout');
        
        // Profile
        Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
        Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
        
        // Change Password
        Route::get('/profile/password', [UserController::class, 'showChangePasswordForm'])->name('profile.password');
        Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password.update');
        
        // Cart & Orders (placeholders)
        Route::get('/cart', [UserController::class, 'showCart'])->name('cart');
        Route::post('/cart/update', [UserController::class, 'updateCart'])->name('cart.update');
        Route::post('/cart/remove', [UserController::class, 'removeFromCart'])->name('cart.remove');
        Route::get('/orders', [UserController::class, 'showOrders'])->name('orders');
    });
});

Route::post('/cart/add', [UserController::class, 'addToCart'])->name('cart.add');

// Product review route
Route::post('/product/review', [UserController::class, 'storeProductReview'])->name('product.review.store');
Route::post('/product/review/delete', [UserController::class, 'deleteProductReview'])->name('product.review.delete');

// Language route
Route::get('/language/{locale}', [LanguageController::class, 'changeLanguage'])->name('language.change');

// Routes xác thực cơ bản
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Routes cho admin
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    // Routes không cần auth
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AdminController::class, 'login'])->name('login.submit');

    // Routes cần auth và role admin
    Route::middleware(['auth', 'admin'])->group(function () {
        // Dashboard
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Quản lý nhân viên
        Route::prefix('staff')->name('staff.')->group(function () {
            Route::get('/', [AdminController::class, 'staffIndex'])->name('index');
            Route::post('/', [AdminController::class, 'createMedicalStaff'])->name('store');
            Route::post('/{userId}/toggle-lock', [AdminController::class, 'toggleUserLock'])->name('toggle-lock');
            Route::put('/{userId}', [AdminController::class, 'updateStaff'])->name('update');
        });

        // Quản lý thuốc
        Route::prefix('medicine')->name('medicine.')->group(function () {
            Route::get('/', [AdminController::class, 'medicineIndex'])->name('index');
            Route::post('/', [AdminController::class, 'createMedicine'])->name('store');
            Route::put('/{medicineId}', [AdminController::class, 'updateMedicine'])->name('update');
            Route::delete('/{medicineId}', [AdminController::class, 'deleteMedicine'])->name('delete');
        });

        // Quản lý trị liệu
        Route::prefix('treatment')->name('treatment.')->group(function () {
            Route::get('/', [AdminController::class, 'treatmentIndex'])->name('index');
            Route::post('/', [AdminController::class, 'createTreatment'])->name('store');
            Route::put('/{treatmentId}', [AdminController::class, 'updateTreatment'])->name('update');
            Route::delete('/{treatmentId}', [AdminController::class, 'deleteTreatment'])->name('delete');
        });

        // Quản lý doanh thu
        Route::get('/revenue', [AdminController::class, 'revenueIndex'])->name('revenue');
        Route::get('/revenue/data', [AdminController::class, 'getRevenueData'])->name('revenue.data');

        // Đăng xuất
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

        // Cập nhật trạng thái user
        Route::post('/users/{userId}/status', [AdminController::class, 'updateUserStatus'])->name('users.update-status');
    });

    // Routes cho quản lý thuốc
    Route::get('/medicine', [AdminController::class, 'medicineIndex'])->name('admin.medicine.index');
    Route::post('/medicine', [AdminController::class, 'medicineStore'])->name('admin.medicine.store');
    Route::put('/medicine/{id}', [AdminController::class, 'medicineUpdate'])->name('admin.medicine.update');
    Route::delete('/medicine/{id}', [AdminController::class, 'medicineDestroy'])->name('admin.medicine.destroy');
    
    Route::get('/treatment', [AdminController::class, 'treatmentIndex'])->name('admin.treatment.index');
    Route::post('/treatment', [AdminController::class, 'treatmentStore'])->name('admin.treatment.store');
    Route::put('/treatment/{id}', [AdminController::class, 'treatmentUpdate'])->name('admin.treatment.update');
    Route::delete('/treatment/{id}', [AdminController::class, 'treatmentDestroy'])->name('admin.treatment.destroy');
});

// Route tạm thởi để reset mật khẩu admin (Xóa route này sau khi đã reset xong)
Route::get('/reset-admin-password', function() {
    $admin = \App\Models\User::where('email', 'admin@gmail.com')->first();
    if($admin) {
        $admin->password = \Illuminate\Support\Facades\Hash::make('123456');
        $admin->save();
        return "Đã reset mật khẩu admin thành: 123456";
    }
    return "Không tìm thấy tài khoản admin";
});

Route::post('/admin/users/{userId}/status', [AdminController::class, 'updateUserStatus'])->name('admin.users.updateStatus');

Route::put('/admin/staff/{id}', [AdminController::class, 'updateStaff'])->name('admin.staff.update');

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Quản lý nhân viên
    Route::get('/staff', [AdminController::class, 'staffIndex'])->name('staff.index');
    Route::post('/staff/store', [AdminController::class, 'createMedicalStaff'])->name('staff.store');
    Route::post('/users/{userId}/status', [AdminController::class, 'updateUserStatus'])->name('users.updateStatus');
    
    // Quản lý thuốc
    Route::get('/medicine', [AdminController::class, 'medicineIndex'])->name('medicine.index');
    Route::post('/medicine', [AdminController::class, 'medicineStore'])->name('medicine.store');
    Route::put('/medicine/{id}', [AdminController::class, 'updateMedicine'])->name('medicine.update');
    Route::delete('/medicine/{id}', [AdminController::class, 'deleteMedicine'])->name('medicine.destroy');
    
    // Quản lý trị liệu
    Route::get('/treatment', [AdminController::class, 'treatmentIndex'])->name('treatment.index');
    Route::post('/treatment', [AdminController::class, 'treatmentStore'])->name('treatment.store');
    Route::put('/treatment/{id}', [AdminController::class, 'updateTreatment'])->name('treatment.update');
    Route::delete('/treatment/{id}', [AdminController::class, 'deleteTreatment'])->name('treatment.destroy');

    // Quản lý khách hàng
    Route::get('/customers', [AdminController::class, 'customerIndex'])->name('customers.index');
    Route::post('/customers/{userId}/status', [AdminController::class, 'updateCustomerStatus'])->name('customers.updateStatus');

    // Quản lý thành viên
    Route::get('/member', [AdminController::class, 'memberIndex'])->name('member.index');
    Route::post('/member', [AdminController::class, 'memberStore'])->name('member.store');
    Route::put('/member/{id}', [AdminController::class, 'memberUpdate'])->name('member.update');
    Route::post('/member/{userId}/status', [AdminController::class, 'updateMemberStatus'])->name('member.updateStatus');
});

// Routes đăng nhập cho dược sĩ
Route::group(['prefix' => 'pharmacist'], function () {
    Route::get('/login', [PharmacistLoginController::class, 'showLoginForm'])->name('pharmacist.login');
    Route::post('/login', [PharmacistLoginController::class, 'login'])->name('pharmacist.login.submit');
    Route::post('/logout', [PharmacistLoginController::class, 'logout'])->name('pharmacist.logout');
});

// Routes cho dược sĩ sau khi đăng nhập
Route::prefix('pharmacist')->name('pharmacist.')->group(function () {
    // Route đăng nhập
    Route::get('/login', [App\Http\Controllers\Auth\PharmacistLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\PharmacistLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [App\Http\Controllers\Auth\PharmacistLoginController::class, 'logout'])->name('logout');
    
    // Route cho người dùng đã đăng nhập
    Route::middleware(['auth', 'pharmacist'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'index'])->name('dashboard');
        
        // Quản lý đơn thuốc
        Route::prefix('prescriptions')->name('prescriptions.')->group(function () {
            Route::get('/pending', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'pendingPrescriptions'])->name('pending');
            Route::get('/history', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'prescriptionHistory'])->name('history');
            Route::get('/{id}', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'showPrescription'])->name('show');
            Route::post('/{id}/process', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'processPrescription'])->name('process');
        });
        
        // Quản lý kho
        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'inventoryIndex'])->name('index');
            Route::get('/import', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'importForm'])->name('import');
            Route::post('/import', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'processImport'])->name('import.process');
            Route::get('/export', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'exportInventory'])->name('export');
        });
        
        // Quản lý đơn hàng
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'ordersIndex'])->name('index');
            Route::get('/create', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'createOrder'])->name('create');
            Route::post('/', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'storeOrder'])->name('store');
            Route::get('/{id}', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'showOrder'])->name('show');
        });
        
        // Quản lý đổi trả
        Route::prefix('returns')->name('returns.')->group(function () {
            Route::get('/', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'returnsIndex'])->name('index');
            Route::get('/create/{orderId}', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'createReturn'])->name('create');
            Route::post('/', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'storeReturn'])->name('store');
            Route::get('/{id}', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'showReturn'])->name('show');
        });
    });
});
