<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.index'); // Pastikan view ini ada di resources/views/settings/index.blade.php
    }

    public function update(Request $request)
    {
        // Validasi request
        $request->validate([
            'app_name' => 'required|string|max:255',
        ]);

        // Simpan data ke konfigurasi atau database (sesuai kebutuhan)
        // Contoh jika menyimpan ke .env atau config
        config(['app.name' => $request->app_name]);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('settings.index')->with('success', 'Settings updated successfully');
    }
}
