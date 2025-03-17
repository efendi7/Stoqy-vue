<?php
namespace App\Services;

use App\Interfaces\SettingRepositoryInterface;
use Illuminate\Http\Request;

class SettingService
{
    protected $settingRepository;

    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function getSettings()
    {
        return $this->settingRepository->getSetting();
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'app_name' => $request->app_name,
            'app_logo' => $request->hasFile('app_logo') ? $request->file('app_logo') : null,
        ];

        return $this->settingRepository->updateSetting($data);
    }
}
