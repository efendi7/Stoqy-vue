<?php
namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityHelper {
    public static function log($description, $module) {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => $description,
            'module' => $module
        ]);
    }
}
