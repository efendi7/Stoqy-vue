<?php

// File: app/Traits/LogsActivity.php

namespace App\Traits;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Log an activity
     *
     * @param string $action
     * @param string $module
     * @param string $description
     * @param array $properties
     * @return void
     */
    protected function logActivity(string $action, string $module, string $description = null, array $properties = [])
    {
        if (!Auth::check()) {
            return;
        }

        Activity::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    /**
     * Log a create action
     *
     * @param string $module
     * @param mixed $model
     * @return void
     */
    protected function logCreate(string $module, $model)
    {
        $description = "Created new {$module}: " . $this->getModelIdentifier($model);
        $this->logActivity('create', $module, $description, [
            'model_id' => $model->id,
            'data' => $model->toArray()
        ]);
    }

    /**
     * Log a read action
     *
     * @param string $module
     * @param mixed $model
     * @return void
     */
    protected function logRead(string $module, $model)
    {
        $description = "Viewed {$module}: " . $this->getModelIdentifier($model);
        $this->logActivity('read', $module, $description, [
            'model_id' => $model->id
        ]);
    }

    /**
     * Log an update action
     *
     * @param string $module
     * @param mixed $model
     * @param array $originalData
     * @param array $changes
     * @return void
     */
    protected function logUpdate(string $module, $model, array $originalData = [], array $changes = [])
    {
        $description = "Updated {$module}: " . $this->getModelIdentifier($model);
        $this->logActivity('update', $module, $description, [
            'model_id' => $model->id,
            'original' => $originalData,
            'changes' => $changes
        ]);
    }

    /**
     * Log a delete action
     *
     * @param string $module
     * @param mixed $model
     * @return void
     */
    protected function logDelete(string $module, $model)
    {
        $description = "Deleted {$module}: " . $this->getModelIdentifier($model);
        $this->logActivity('delete', $module, $description, [
            'model_id' => $model->id,
            'data' => $model->toArray()
        ]);
    }

    /**
     * Get a human-readable identifier for the model
     *
     * @param mixed $model
     * @return string
     */
    protected function getModelIdentifier($model)
    {
        // Try commonly used identifiers
        foreach (['name', 'title', 'code', 'sku', 'email', 'username'] as $field) {
            if (isset($model->$field)) {
                return $model->$field;
            }
        }

        // Fall back to ID
        return "#" . $model->id;
    }
}