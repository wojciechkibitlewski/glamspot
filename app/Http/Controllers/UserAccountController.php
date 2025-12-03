<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserAccountController extends Controller
{
    public function index()
    {
        return view('sites/user-accounts/index');
    }

    public function password()
    {
        return view('sites/user-accounts/password');
    }

    public function billing()
    {
        return view('sites/user-accounts/billing');
    }

    public function notifications()
    {
        return view('sites/user-accounts/notifications');
    }

    public function newsletter()
    {
        return view('sites/user-accounts/newsletter');
    }

    public function show(string $code)
    {
        $user = User::query()->where('code', $code)->firstOrFail();

        return view('sites/user-accounts/show', [
            'user' => $user,
        ]);
    }

}
