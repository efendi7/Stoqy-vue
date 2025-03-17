<?php
namespace App\Repositories;

use App\Models\Setting;
use App\Interfaces\SettingRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class SettingRepository implements SettingRepositoryInterface
{
    public function getSetting(): ?Setting
    {
        return Setting::first();
    }

    public function updateSetting(array $data): Setting
    {
        $setting = Setting::firstOrNew([]);

        $setting->app_name = $data['app_name'];

        if (isset($data['app_logo'])) {
            if ($setting->logo && Storage::exists('public/' . $setting->logo)) {
                Storage::delete('public/' . $setting->logo);
            }

            $path = $data['app_logo']->store('logos', 'public');
            $setting->logo = $path;
        }

        $setting->save();

        return $setting;
    }
}
