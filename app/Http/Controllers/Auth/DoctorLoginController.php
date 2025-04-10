<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class DoctorLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/doctor/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('doctor.auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            // Kiểm tra xem người dùng có phải là bác sĩ không
            if (Auth::user()->id_role == 2) {
                return $this->sendLoginResponse($request);
            } else {
                Auth::logout();
                return redirect()->back()
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors(['email' => 'Tài khoản này không có quyền truy cập vào hệ thống bác sĩ.']);
            }
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/doctor/login');
    }
} 