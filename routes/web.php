<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\HomeController;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', [UserController::class,'index'])-> name('index');

Route::get('/product/{slug}', [UserController::class,'product'])-> name('product');

Route::get('/doctor/{slug}', [UserController::class,'doctor'])-> name('doctor');

Route::get('/detailsp/{slug}', [UserController::class,'detailsp'])-> name('detailsp');

Route::get('/about/', [UserController::class,'about'])-> name('about');

Route::get('/service/', [UserController::class,'service'])-> name('service');

Route::get('/contact/', [UserController::class,'contact'])-> name('contact');

Route::get('/department/', [UserController::class,'department'])-> name('department');

Route::get('/admin/index', function () {
    return view('admin.index');
});

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
