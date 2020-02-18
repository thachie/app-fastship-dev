<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class LanguageController extends Controller
{
    
    public function setLocale($locale='th'){
        if (!in_array($locale, ['en', 'th'])){
            $locale = 'th';
        }
        Session::put('lang', $locale);
        return redirect(url(URL::previous()));
    }
}
