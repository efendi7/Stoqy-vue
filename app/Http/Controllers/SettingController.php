<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function update(Request $request)
    {
        // Perbaikan: Typo pada validate
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Ambil data pertama atau buat baru jika tidak ada
        $setting = Setting::firstOrNew([]);

        $setting->app_name = $request->app_name;

        if ($request->hasFile('app_logo')) {
            // Hapus  logo lama jika ada
            if ($setting->logo && Storage::exists('public/' . $setting->logo)) {
                Storage::delete('public/' . $setting->logo);
            }

            // Simpan logo baru di storage/public/logos
            $path = $request->file('app_logo')->store('logos', 'public');
            $setting->logo = $path;
        }

        $setting->save();
        

        return redirect()->back()->with('success', 'Settings updated successfully.');
        
    }
}
