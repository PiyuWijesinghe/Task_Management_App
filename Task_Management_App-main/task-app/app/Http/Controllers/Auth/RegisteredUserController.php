<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Accept either 'username' (preferred) or 'name' (tests / older views)
        $request->validate([
            'username' => ['sometimes', 'nullable', 'string', 'max:255', 'alpha_dash', 'unique:users,username'],
            'name' => ['required_without:username', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $usernameValue = $request->input('username') ?? preg_replace('/\s+/', '-', strtolower($request->input('name')));

        $user = User::create([
            'name' => $request->input('name') ?? $usernameValue,
            'username' => $usernameValue,
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        event(new Registered($user));

        // Auto-login the newly registered user to match test expectations
        Auth::login($user);

        return redirect(route('dashboard'));
    }
}
