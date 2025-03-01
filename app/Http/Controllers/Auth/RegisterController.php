<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Menampilkan form registrasi
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Menangani proses registrasi
    public function register(Request $request)
    {
        // Validasi data input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Cek apakah ini user pertama
        $isFirstUser = User::count() == 0;

        // Jika user pertama, set role sebagai 'Staff Gudang', jika tidak default ke 'User'
        $role = $isFirstUser ? 'Staff Gudang' : 'User';

        // Membuat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role, 
            'email_verified_at' => now(), // Auto-verifikasi email
        ]);

        // Login otomatis setelah registrasi
        Auth::login($user);

        // Redirect ke halaman dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil!');
    }
}
