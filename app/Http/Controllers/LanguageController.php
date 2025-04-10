<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Change the application language
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $locale
     * @return \Illuminate\Http\Response
     */
    public function changeLanguage($locale)
    {
        // Validate if locale is supported
        if (!in_array($locale, ['en', 'vi'])) {
            $locale = 'en'; // Default to English if not supported
        }
        
        // Store the locale in session
        Session::put('locale', $locale);
        
        // Redirect back to previous page
        return back();
    }
} 