<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Administrator;

class AuthController extends Controller
{
    /**
     * Display login form
     */
    public function FormLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login from both users and administrators tables
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Try login as Administrator first (super_admin or admin)
        $administrator = Administrator::where('email_admin', $credentials['email'])->first();
        
        if ($administrator && Hash::check($credentials['password'], $administrator->password_admin)) {
            // Login as Administrator
            Auth::guard('administrator')->login($administrator);
            $request->session()->regenerate();

            // Redirect based on role_admin
            if ($administrator->role_admin === 'super_admin') {
                return redirect()->route('superadmin.dashboard');
            } else {
                return redirect()->route('admin.dashboard');
            }
        }

        // Try login as User (petugas)
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('petugas.dashboard');
        }

        // If both attempts fail
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        // Check which guard is authenticated and logout
        if (Auth::guard('administrator')->check()) {
            Auth::guard('administrator')->logout();
        } elseif (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
}
