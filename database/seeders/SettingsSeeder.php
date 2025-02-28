<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah sudah ada setting
        if (Setting::count() == 0) {
            Setting::create([
                'app_name' => 'Efendi Stockify',
                'logo' => 'default-logo.png',
                // Tambahkan field lain yang diperlukan
            ]);
            
            // Copy logo default ke storage jika perlu
            if (!file_exists(storage_path('app/public/default-logo.png'))) {
                copy(
                    public_path('img/logofenn.png'),
                    storage_path('app/public/default-logo.png')
                );
            }
        }
    }
}