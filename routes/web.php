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
use App\Http\Controllers\Doctor\ScheduleController;
use App\Http\Controllers\Doctor\DoctorScheduleController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Auth\DoctorLoginController;
use App\Http\Controllers\User\OrderController;

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
Route::get('/doctors/', [UserController::class,'doctor'])-> name('doctors');
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
        
        // Socialite Authentication Routes
        Route::get('/auth/{provider}', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirectToProvider'])->name('socialite.redirect');
        Route::get('/auth/{provider}/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'handleProviderCallback'])->name('socialite.callback');
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
        Route::post('/orders', [UserController::class, 'placeOrder'])->name('orders.store');
        Route::get('/orders/{id}', [UserController::class, 'showOrder'])->name('orders.show');
        Route::get('/checkout', [UserController::class, 'checkout'])->name('checkout');
        Route::post('/order/store', [UserController::class, 'placeOrder'])->name('order.store');
    });
});

// Các route cho chức năng của User
Route::middleware(['auth'])->group(function () {
    // Giỏ hàng
    Route::get('/cart', [UserController::class, 'showCart'])->name('cart');
    Route::post('/cart/update', [UserController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove', [UserController::class, 'removeFromCart'])->name('cart.remove');
    
    // Đơn hàng
    Route::get('/orders', [UserController::class, 'showOrders'])->name('orders');
    Route::post('/order/store', [UserController::class, 'placeOrder'])->name('order.store');
    Route::get('/checkout', [UserController::class, 'checkout'])->name('checkout');
    
    // Thanh toán
    Route::post('/create-payment-intent', [UserController::class, 'createPaymentIntent'])->name('user.create-payment-intent');
});

// Route thêm vào giỏ hàng không yêu cầu đăng nhập (đặt ở ngoài middleware auth)
Route::post('/cart/add', [UserController::class, 'addToCart'])->name('cart.add');

// Order store route
Route::post('/user/order/store', [UserController::class, 'placeOrder'])->name('order.store');

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
    Route::get('/login', [App\Http\Controllers\Admin\AdminController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [App\Http\Controllers\Admin\AdminController::class, 'login'])->name('login.submit');

    // Routes cần auth và role admin
    Route::middleware(['auth', 'admin'])->group(function () {
        // Dashboard
        Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');

        // Quản lý mỹ phẩm
        Route::prefix('cosmetics')->name('cosmetics.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\CosmeticController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\Admin\CosmeticController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [App\Http\Controllers\Admin\CosmeticController::class, 'edit'])->name('edit');
            Route::put('/{id}', [App\Http\Controllers\Admin\CosmeticController::class, 'update'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\Admin\CosmeticController::class, 'destroy'])->name('destroy');
        });

        // Quản lý lịch làm việc của bác sĩ
        Route::prefix('schedules')->name('schedules.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ScheduleController::class, 'index'])->name('index');
            Route::get('/doctor/{doctorId}', [App\Http\Controllers\Admin\ScheduleController::class, 'doctorSchedule'])->name('doctor');
            Route::get('/doctor/{doctorId}/create', [App\Http\Controllers\Admin\ScheduleController::class, 'create'])->name('create');
            Route::post('/doctor/{doctorId}', [App\Http\Controllers\Admin\ScheduleController::class, 'store'])->name('store');
            Route::get('/doctor/{doctorId}/schedule/{id}/edit', [App\Http\Controllers\Admin\ScheduleController::class, 'edit'])->name('edit');
            Route::put('/doctor/{doctorId}/schedule/{id}', [App\Http\Controllers\Admin\ScheduleController::class, 'update'])->name('update');
            Route::delete('/doctor/{doctorId}/schedule/{id}', [App\Http\Controllers\Admin\ScheduleController::class, 'destroy'])->name('destroy');
        });

        // Quản lý nhân viên
        Route::prefix('staff')->name('staff.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'staffIndex'])->name('index');
            Route::post('/', [App\Http\Controllers\Admin\AdminController::class, 'createMedicalStaff'])->name('store');
            Route::post('/{userId}/toggle-lock', [App\Http\Controllers\Admin\AdminController::class, 'toggleUserLock'])->name('toggle-lock');
            Route::put('/{userId}', [App\Http\Controllers\Admin\AdminController::class, 'updateStaff'])->name('update');
        });

        // Quản lý thuốc
        Route::prefix('medicine')->name('medicine.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'medicineIndex'])->name('index');
            Route::post('/', [App\Http\Controllers\Admin\AdminController::class, 'medicineStore'])->name('store');
            Route::get('/{id}', [App\Http\Controllers\Admin\AdminController::class, 'showMedicine'])->name('show');
            Route::put('/{id}', [App\Http\Controllers\Admin\AdminController::class, 'updateMedicine'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\Admin\AdminController::class, 'deleteMedicine'])->name('destroy');
        });

        // Quản lý trị liệu
        Route::prefix('treatment')->name('treatment.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'treatmentIndex'])->name('index');
            Route::post('/', [App\Http\Controllers\Admin\AdminController::class, 'treatmentStore'])->name('store');
            Route::get('/{id}', [App\Http\Controllers\Admin\AdminController::class, 'showTreatment'])->name('show');
            Route::put('/{id}', [App\Http\Controllers\Admin\AdminController::class, 'updateTreatment'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\Admin\AdminController::class, 'deleteTreatment'])->name('destroy');
        });

        // Quản lý doanh thu
        Route::get('/revenue', [App\Http\Controllers\Admin\AdminController::class, 'revenueIndex'])->name('revenue.index');
        Route::get('/revenue/data', [App\Http\Controllers\Admin\AdminController::class, 'getRevenueData'])->name('revenue.data');

        // Quản lý đơn hàng
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('index');
            Route::get('/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('show');
            Route::put('/{id}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('update-status');
            Route::get('/export/excel', [App\Http\Controllers\Admin\OrderController::class, 'exportExcel'])->name('export.excel');
            Route::get('/export/pdf', [App\Http\Controllers\Admin\OrderController::class, 'exportPdf'])->name('export.pdf');
            Route::get('/{id}/invoice', [App\Http\Controllers\Admin\OrderController::class, 'invoice'])->name('invoice');
        });

        // Quản lý thành viên
        Route::prefix('member')->name('member.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'memberIndex'])->name('index');
            Route::post('/', [App\Http\Controllers\Admin\AdminController::class, 'memberStore'])->name('store');
            Route::put('/{id}', [App\Http\Controllers\Admin\AdminController::class, 'memberUpdate'])->name('update');
            Route::post('/{userId}/status', [App\Http\Controllers\Admin\AdminController::class, 'updateMemberStatus'])->name('updateStatus');
            Route::get('/{id}/edit', [App\Http\Controllers\Admin\AdminController::class, 'getMemberInfo'])->name('edit');
        });

        // Đăng xuất
        Route::post('/logout', [App\Http\Controllers\Admin\AdminController::class, 'logout'])->name('logout');

        // Cập nhật trạng thái user - cần match với URL trong JavaScript
        Route::match(['post', 'put'], '/users/{id}/status', [App\Http\Controllers\Admin\AdminController::class, 'manageAccountStatus'])->name('users.status');
    });
});

// Routes cho bác sĩ
Route::prefix('doctor')->name('doctor.')->group(function () {
    // Routes không cần auth
    Route::get('/login', [DoctorLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [DoctorLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [DoctorLoginController::class, 'logout'])->name('logout');

    // Routes cần auth và role doctor
    Route::middleware(['auth', 'doctor'])->group(function () {
        // Dashboard
        Route::get('/', [DoctorController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');

        // Lịch làm việc
        Route::prefix('schedules')->name('schedules.')->group(function () {
            Route::get('/', [DoctorScheduleController::class, 'index'])->name('index');
            Route::get('/week', [DoctorScheduleController::class, 'showWeek'])->name('week');
            Route::get('/create', [DoctorScheduleController::class, 'create'])->name('create');
            Route::post('/', [DoctorScheduleController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [DoctorScheduleController::class, 'edit'])->name('edit');
            Route::put('/{id}', [DoctorScheduleController::class, 'update'])->name('update');
            Route::delete('/{id}', [DoctorScheduleController::class, 'destroy'])->name('destroy');
        });

        // Quản lý bệnh nhân
        Route::prefix('patients')->name('patients.')->group(function () {
            // Danh sách bệnh nhân chờ khám
            Route::get('/pending', [DoctorController::class, 'pendingPatients'])->name('pending');
            
            // Lịch sử khám bệnh
            Route::get('/history', [DoctorController::class, 'patientHistory'])->name('history');
            
            // Xem chi tiết bệnh án
            Route::get('/{id}', [DoctorController::class, 'showMedicalRecord'])->name('show');
            
            // Form khám bệnh
            Route::get('/{id}/examination', [DoctorController::class, 'showExamination'])->name('examination');
            
            // Lưu kết quả khám
            Route::post('/{id}/examination', [DoctorController::class, 'saveExamination'])->name('save_examination');
        });

        // Quản lý đơn thuốc
        Route::prefix('prescriptions')->name('prescriptions.')->group(function () {
            Route::get('/', [DoctorController::class, 'prescriptionIndex'])->name('index');
            Route::get('/{id}', [DoctorController::class, 'showPrescription'])->name('show');
        });
    });
});

// API routes
Route::prefix('api')->name('api.')->group(function () {
    // Bác sĩ có lịch làm việc vào một ngày cụ thể
    Route::get('/doctors/available', [DoctorScheduleController::class, 'getDoctorsAvailableOn'])->name('doctors.available');
    
    // Lấy bác sĩ có lịch làm việc vào một ngày cụ thể (cho đặt lịch hẹn)
    Route::get('/appointments/doctors', [App\Http\Controllers\User\AppointmentController::class, 'getDoctorsAvailableOn'])->name('appointments.doctors');
    
    // Lấy khung giờ làm việc của bác sĩ vào một ngày cụ thể
    Route::get('/appointments/timeslots', [App\Http\Controllers\User\AppointmentController::class, 'getDoctorAvailableTimeSlots'])->name('appointments.timeslots');
});

// Routes cho dược sĩ 
Route::prefix('pharmacist')->name('pharmacist.')->group(function () {
    // Routes không cần auth
    Route::get('/login', [App\Http\Controllers\Auth\PharmacistLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\PharmacistLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [App\Http\Controllers\Auth\PharmacistLoginController::class, 'logout'])->name('logout');
    
    // Routes cần auth và role pharmacist
    Route::middleware(['auth', 'pharmacist'])->group(function () {
        // Dashboard
        Route::get('/', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'index'])->name('dashboard');
        
        // Quản lý đơn thuốc
        Route::prefix('prescriptions')->name('prescriptions.')->group(function () {
            Route::get('/pending', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'pendingPrescriptions'])->name('pending');
            Route::get('/history', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'prescriptionHistory'])->name('history');
            Route::get('/{id}', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'showPrescription'])->name('show');
            Route::post('/{id}/process', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'processPrescription'])->name('process');
            Route::get('/{id}/print', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'printPrescription'])->name('print');
            Route::post('/{id}/payment/intent', [PharmacistController::class, 'createPaymentIntent'])->name('prescriptions.payment.intent');
        });
        
        // Tiếp nhận bệnh nhân và đơn thuốc
        Route::prefix('patients')->name('patients.')->group(function () {
            Route::get('/receive', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'receivePatient'])->name('receive');
            Route::post('/process', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'processPatient'])->name('process');
            Route::get('/medical-record/{id}/prescription', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'receivePrescription'])->name('prescription');
            Route::post('/medical-record/{id}/complete', [App\Http\Controllers\Pharmacist\PharmacistController::class, 'completePrescription'])->name('complete');
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

// Các route cho quản lý đơn hàng
Route::get('/orders', [UserController::class, 'showOrders'])->name('orders');
Route::get('/api/order-items', [UserController::class, 'getOrderItems'])->name('api.order.items');
Route::post('/order/cancel', [UserController::class, 'cancelOrder'])->name('order.cancel');

// Nhóm các route cho user
Route::prefix('user')->name('user.')->middleware(['auth', 'checkUserRole:3'])->group(function () {
    // Nhóm các route cho appointment
    Route::prefix('appointment')->name('appointment.')->group(function () {
        Route::get('/get-doctors-available', [App\Http\Controllers\User\AppointmentController::class, 'getDoctorsAvailable'])->name('getDoctorsAvailable');
    });
});
