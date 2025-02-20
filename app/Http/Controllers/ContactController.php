<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        // Validasi data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'message' => 'required|string',
        ]);

        // Lakukan sesuatu dengan data kontak, misalnya simpan ke database atau kirim email
        
        // Berikan respon sukses
        return redirect()->back()->with('success', 'Pesan Anda telah dikirim!');
    }
}
