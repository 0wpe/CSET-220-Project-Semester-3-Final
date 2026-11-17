<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show the login view
    public function showLogin()
    {
        // If already logged in, redirect them to their respective dashboard
        if (Auth::check()) {
            return $this->authenticatedRedirect(Auth::user());
        }

        return view('login');
    }

    // Process the login form submission
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to authenticate using the custom 'Users' table model
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->authenticatedRedirect(Auth::user());
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Helper method for role-based redirects
    protected function authenticatedRedirect($user)
    {
        if ($user->role === 'Admin') {
            return redirect()->intended('/admin/dashboard');
        }

        return redirect()->intended('/employee/dashboard');
    }
}
