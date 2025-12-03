<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersAdminController extends Controller
{
    //
    public function index()
    {
        return view('sites/admin/users/index', [
            
        ]);
    }
}
