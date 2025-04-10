<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('locale')) {
            // Nếu có locale trong session, sử dụng nó
            App::setLocale(Session::get('locale'));
        } else {
            // Mặc định sử dụng tiếng Anh
            App::setLocale('en');
        }
        
        return $next($request);
    }
} 