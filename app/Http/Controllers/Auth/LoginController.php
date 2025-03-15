<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login attempt
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
        \Log::info('Login successful for email: ' . $request->input('email'));

        $request->session()->regenerate(); // Regenerate session to prevent session fixation

        if (Auth::check()) {
            Auth::user()->update(['is_logged_in' => true]);

            // Cek apakah user memiliki role atau tidak
            if (Auth::user()->role === null || Auth::user()->role === 'pending') {
                return redirect()->route('request.role.page')->with('warning', 'Silakan ajukan role Anda sebelum mengakses sistem.');
            }

            return redirect()->route('dashboard')->with('success', 'Login berhasil');
        }
    }

    \Log::warning('Login failed for email: ' . $request->input('email'));

    // Return back with error if login fails
    return back()->withErrors(['email' => 'Email atau kata sandi salah.'])->withInput($request->only('email'));
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

        $request->session()->invalidate(); // Hapus sesi lama
        $request->session()->regenerateToken(); // Cegah CSRF setelah logout

        return redirect('/login')->with('success', 'Logout berhasil');
    }
}
