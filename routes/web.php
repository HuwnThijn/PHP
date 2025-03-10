<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\LoginController;

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
Route::get('/doctor/{slug}', [UserController::class,'doctor'])-> name('doctor');
Route::get('/detailsp/{slug}', [UserController::class,'detailsp'])-> name('detailsp');
Route::get('/about/', [UserController::class,'about'])-> name('about');
Route::get('/service/', [UserController::class,'service'])-> name('service');
Route::get('/contact/', [UserController::class,'contact'])-> name('contact');
Route::get('/department/', [UserController::class,'department'])-> name('department');

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
});

// Route tạm thời để reset mật khẩu admin (Xóa route này sau khi đã reset xong)
Route::get('/reset-admin-password', function() {
    $admin = \App\Models\User::where('email', 'admin@gmail.com')->first();
    if($admin) {
        $admin->password = \Illuminate\Support\Facades\Hash::make('123456');
        $admin->save();
        return "Đã reset mật khẩu admin thành: 123456";
    }
    return "Không tìm thấy tài khoản admin";
});
