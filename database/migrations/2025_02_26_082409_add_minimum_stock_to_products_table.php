<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Simpan nama aplikasi di .env atau database
        $envPath = base_path('.env');
        file_put_contents($envPath, str_replace(
            'APP_NAME=' . env('APP_NAME'),
            'APP_NAME=' . $request->app_name,
            file_get_contents($envPath)
        ));

        // Simpan logo aplikasi
        if ($request->hasFile('app_logo')) {
            $path = $request->file('app_logo')->store('logos', 'public');
            // Simpan path logo di .env atau database
            file_put_contents($envPath, str_replace(
                'APP_LOGO=' . env('APP_LOGO'),
                'APP_LOGO=' . $path,
                file_get_contents($envPath)
            ));
        }

        return redirect()->route('settings')->with('success', 'Settings updated successfully.');
    }
}
