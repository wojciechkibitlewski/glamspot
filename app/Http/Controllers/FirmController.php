<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FirmController extends Controller
{
    public function index()
    {
        return view('sites/firms/index');
    }

    public function show()
    {
        return view('sites/firms/show');
    }

    public function manage()
    {
        return view('sites/user-accounts/firm');
    }
}
