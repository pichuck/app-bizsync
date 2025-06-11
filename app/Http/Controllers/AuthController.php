<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'uname' => 'required|string',
            'password' => 'required|string',
        ]);

        // Try login with username or email
        $loginField = filter_var($credentials['uname'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        $loginData = [
            $loginField => $credentials['uname'],
            'password' => $credentials['password'],
            'status' => 'active'
        ];

        if (Auth::attempt($loginData, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Force fresh user instance with roles loaded
            $user = Auth::user()->fresh(['roles']);
            
            // Redirect based on role - menggunakan direct relationship check
            if ($user->roles->contains('name', 'super-admin')) {
                return redirect()->intended('/dashboard/super-admin');
            } elseif ($user->roles->contains('name', 'admin')) {
                return redirect()->intended('/dashboard/admin');
            } elseif ($user->roles->contains('name', 'admin-inventory')) {
                return redirect()->intended('/dashboard/inventory');
            } elseif ($user->roles->contains('name', 'admin-finance')) {
                return redirect()->intended('/dashboard/finance');
            }
            
            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'uname' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}