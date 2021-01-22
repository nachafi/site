<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AboutController extends Controller
{    
    public function history()
    {
    return view('site.pages.about.history');
    }
    public function buy()
    {
    return view('site.pages.about.buy');
    }
    public function delivery()
    {
    return view('site.pages.about.delivery');
    }
    public function help()
    {
    return view('site.pages.custumer.help');
    }
    public function money()
    {
    return view('site.pages.custumer.money');
    }
    public function terms()
    {
    return view('site.pages.custumer.terms');
    }
}
