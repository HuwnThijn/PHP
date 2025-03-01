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

    public function login(Request $request){
        $request -> validate([
            'username' => 'required',
            'password' => 'required|min:6'
        ]);

        $credentials = $request->only('username', 'password');

        if(Auth::attempt(array_merge($credentials, ['role' => 'admin']))){
            return redirect()->route('admin.dashboard') -> with('success', 'Đăng nhập thành công!');
        }

        return back() -> withErrors(['username' => 'Tài khoản hoặc mật khẩu không đúng!']);
    }

    /**
     * Đăng xuất Admin
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('success', 'Đăng xuất thành công!');
    }
}
