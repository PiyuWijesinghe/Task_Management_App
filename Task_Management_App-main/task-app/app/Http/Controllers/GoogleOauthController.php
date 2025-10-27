<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleService;

class GoogleOauthController extends Controller
{
    protected $google;

    public function __construct(GoogleService $google)
    {
        $this->google = $google;
    }

    public function redirectToGoogle()
    {
        return redirect($this->google->getAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        $user = $request->user();
        $code = $request->get('code');
        if (! $code) {
            return redirect('/')->with('error', 'No code provided by Google.');
        }

        try {
            $this->google->storeTokenFromAuthCode($user, $code);
        } catch (\Throwable $e) {
            return redirect('/')->with('error', 'Google OAuth failed: ' . $e->getMessage());
        }

        return redirect('/')->with('success', 'Google connected.');
    }
}
