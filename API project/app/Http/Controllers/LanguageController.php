<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switchLang($lang)
    {
        // Check if the language is supported
        if (in_array($lang, ['en', 'ar', 'fr'])) {
            // Set the language in session
            session()->put('locale', $lang);
            // Set the application locale
            app()->setLocale($lang);
        }
        
        return redirect()->back();
    }
}
