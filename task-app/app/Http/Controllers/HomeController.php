<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the home page.
     * If user is authenticated, they can still see the home page but with different content.
     */
    public function index()
    {
        // Show home page for both guests and authenticated users
        // The view will handle showing different content based on authentication status
        return view('home');
    }
}
