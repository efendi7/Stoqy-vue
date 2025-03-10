<?php
// app/helpers.php
function logActivity($userId, $action, $description = null) {
  \App\Models\ActivityLog::create([
      'user_id' => $userId,
      'action' => $action,
      'description' => $description,
      'ip_address' => request()->ip(),
      'user_agent' => request()->header('User-Agent'),
  ]);
}

if (!function_exists('getActionColor')) {
    function getActionColor($action)
    {
        return match ($action) {
            'created' => 'success',
            'updated' => 'warning',
            'deleted' => 'danger',
            default => 'secondary',
        };
    }
}
