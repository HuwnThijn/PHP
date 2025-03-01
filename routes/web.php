<?php

use App\Http\Controllers\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login'); // Hiển thị form đăng nhập
    Route::post('/login', [AdminAuthController::class, 'login']) -> name('admin.login.post'); // Xử lý đăng nhập

    Route::get('/logout', [AdminAuthController::class, 'logout']) -> name('admin.logout'); // Đăng xuất

});