<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    
    public function about()
    {
        return view('sites/static-pages/about');
    }

    public function terms()
    {
        return view('sites/static-pages/terms');
    }

    public function privacy()
    {
        return view('sites/static-pages/privacy');
    }

    public function faq()
    {
        return view('sites/static-pages/faq');
    }

}
