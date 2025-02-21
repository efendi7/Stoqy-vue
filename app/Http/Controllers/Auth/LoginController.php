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

        // Debugging: Check user in the database
        $user = User::where('email', $request->email)->first();
        if ($user) {
            \Log::info('User found: ' . $user->email);
            \Log::info('Stored password hash: ' . $user->password);

            // Manual password check
            if (Hash::check($request->input('password'), $user->password)) {
                \Log::info('Manual password check successful for email: ' . $request->input('email'));
                Auth::login($user);
                return redirect()->intended('dashboard')->with('success', 'Login berhasil');
            } else {
                \Log::info('Manual password check failed for email: ' . $request->input('email'));
            }
        } else {
            \Log::info('User not found with email: ' . $request->email);
        }

        // Attempt to login the user
        if (Auth::attempt($request->only('email', 'password'))) {
            \Log::info('Login attempt successful for email: ' . $request->input('email'));
            return redirect()->intended('dashboard')->with('success', 'Login berhasil');
        } else {
            \Log::info('Login attempt failed for email: ' . $request->input('email'));
            \Log::info('Provided password: ' . $request->input('password'));
            \Log::info('Stored password hash for email ' . $request->input('email') . ' is: ' . optional($user)->password);
        }

        // Return back with error if login fails
        return back()->withErrors(['email' => 'Email atau kata sandi salah.']);
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate(); // Menghapus sesi lama
        $request->session()->regenerateToken(); // Mencegah CSRF setelah logout

        return redirect('/login')->with('success', 'Logout berhasil');
    }
}