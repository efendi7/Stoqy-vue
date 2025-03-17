<?php
namespace App\Http\Controllers;

use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        return view('settings.index');
    }

    public function update(Request $request)
    {
        $this->settingService->updateSettings($request);

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
