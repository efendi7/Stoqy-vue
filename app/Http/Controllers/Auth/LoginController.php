<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login attempt
    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to login the user
        if (Auth::attempt($request->only('email', 'password'))) {
            \Log::info('Login attempt successful for email: ' . $request->input('email'));
            
            // Update is_logged_in status
            Auth::user()->update(['is_logged_in' => true]);

            return redirect()->intended('dashboard')->with('success', 'Login berhasil');
        } else {
            \Log::info('Login attempt failed for email: ' . $request->input('email'));
            \Log::info('Provided password: ' . $request->input('password'));
            \Log::info('Stored password hash for email ' . $request->input('email') . ' is: ' . optional(User::where('email', $request->email)->first())->password);
        }

        // Return back with error if login fails
        return back()->withErrors(['email' => 'Email atau kata sandi salah.']);
    }

    // Handle logout
    public function logout(Request $request)
    {
        // Update is_logged_in status
        $user = Auth::user();
        if ($user) {
            $user->update(['is_logged_in' => false]);
        }

        Auth::logout();
        
        $request->session()->invalidate(); // Menghapus sesi lama
        $request->session()->regenerateToken(); // Mencegah CSRF setelah logout

        return redirect('/login')->with('success', 'Logout berhasil');
    }
}
