<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LoggerService
{
    public static function logError($message, $context = [])
    {
        Log::error($message, $context);
    }

    public static function logInfo($message, $context = [])
    {
        Log::info($message, $context);
    }
}
