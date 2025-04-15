<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfPharmacistAuthenticated
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
        // Nếu người dùng đã đăng nhập và là dược sĩ (id_role = 3)
        if (Auth::check() && Auth::user()->id_role == 3) {
            return redirect('/pharmacist/dashboard');
        }
        
        // Nếu người dùng đã đăng nhập nhưng không phải dược sĩ, cho phép họ đăng nhập lại
        return $next($request);
    }
}
