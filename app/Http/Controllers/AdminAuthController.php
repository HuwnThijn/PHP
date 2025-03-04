<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Đăng nhập Admin
     */

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($credentials['email'] === 'admin' && $credentials['password'] === '123456') {
            Auth::login(User::where('email', 'admin')->first());
            return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!');
        }

        return redirect()->route('admin.login')->with('error', 'Tài khoản hoặc mật khẩu không đúng!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('success', 'Đăng xuất thành công!');
    }
}
