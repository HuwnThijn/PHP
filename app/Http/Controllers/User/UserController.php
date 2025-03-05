<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        return view('user.theme.layout');
    }
    public function product($slug)
    {
        return view('user.theme.product');
    }
    public function detailsp($slug)
    {
        return view('user.theme.detailsp');
    }
    public function doctor($slug)
    {
        return view('user.theme.doctor');
    }
    public function about()
    {
        return view('user.theme.about');
    }
    public function service()
    {
        return view('user.theme.service');
    }
    public function contact()
    {
        return view('user.theme.contact');
    }
    public function department()
    {
        return view('user.theme.department');
    }
}
