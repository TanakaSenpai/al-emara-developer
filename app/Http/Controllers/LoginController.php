<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'boolean',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Check hardcoded admin credentials first
        if ($request->email === 'admin@example.com' && $request->password === 'password') {
            // Create a simple admin user in session
            session(['authenticated' => true, 'user_email' => $request->email]);
            Log::info('Admin logged in', ['email' => $request->email]);

            if ($remember) {
                cookie()->queue('remember_email', $request->email, 60 * 24 * 30); // 30 days
            }

            return redirect()->route('dashboard');
        }

        // If you have a users table with Laravel Auth
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            Log::info('User logged in via Auth', ['email' => $request->email]);
            return redirect()->route('dashboard');
        }

        return back()
            ->withErrors(['email' => 'Invalid email or password.'])
            ->withInput($request->except('password'));
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        session()->forget(['authenticated', 'user_email']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
