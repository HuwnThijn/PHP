<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('user.theme.login');
    }
    
    /**
     * Get the post login redirect path.
     *
     * @return string
     */
    protected function redirectTo()
    {
        if (request()->has('redirect')) {
            return request()->input('redirect');
        }
        
        return RouteServiceProvider::HOME;
    }
} 