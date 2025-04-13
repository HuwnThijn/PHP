<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfDoctorAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Nếu người dùng đã đăng nhập và là bác sĩ (id_role = 2)
        if (Auth::check() && Auth::user()->id_role == 2) {
            return redirect('/doctor/dashboard');
        }
        
        // Nếu người dùng đã đăng nhập nhưng không phải bác sĩ, cho phép họ đăng nhập lại
        return $next($request);
    }
} 