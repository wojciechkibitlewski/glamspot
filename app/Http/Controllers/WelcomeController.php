<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // $featuredAds = Ad::query()
        //     ->with(['category', 'photos', 'user.firm', 'job', 'training'])
        //     ->where('status', 'active')
        //     ->where('is_featured', true)
        //     ->whereNotNull('published_at')
        //     ->whereNotNull('expires_at')
        //     ->where('published_at', '<=', now())
        //     ->where('expires_at', '>=', now())
        //     ->inRandomOrder()
        //     ->take(3)
        //     ->get();

        return view('sites/welcome/index', [
            // 'featuredAds' => $featuredAds,
        ]);
    }
}
