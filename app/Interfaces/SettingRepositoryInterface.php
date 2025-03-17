<?php
namespace App\Interfaces;

use App\Models\Setting;

interface SettingRepositoryInterface
{
    public function getSetting(): ?Setting;
    public function updateSetting(array $data): Setting;
}
