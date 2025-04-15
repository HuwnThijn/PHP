<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorMiddleware
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
        // Nếu người dùng chưa đăng nhập hoặc không phải là bác sĩ (role_id = 2)
        if (!Auth::check() || Auth::user()->id_role != 2) {
            // Nếu là AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Không có quyền truy cập.'], 403);
            }
            
            // Đối với request thông thường, chuyển hướng đến trang đăng nhập
            return redirect()->route('doctor.login')
                ->with('error', 'Vui lòng đăng nhập với tài khoản bác sĩ để tiếp tục.');
        }
        
        return $next($request);
    }
} 