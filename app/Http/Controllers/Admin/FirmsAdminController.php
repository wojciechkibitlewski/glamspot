<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FirmsAdminController extends Controller
{
    //
    public function index()
    {
        return view('sites/admin/firms/index', [
            
        ]);
    }
}
