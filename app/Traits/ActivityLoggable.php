<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait ActivityLoggable 
{
    public static function bootActivityLoggable()
    {
        // Log saat membuat record baru
        static::created(function ($model) {
            $model->logActivity('create');
        });

        // Log saat update
        static::updated(function ($model) {
            $changes = $model->getChanges();
            
            // Hanya log jika ada perubahan meaningful
            if (!empty($changes)) {
                $model->logActivity('update', $changes);
            }
        });

        // Log saat delete
        static::deleted(function ($model) {
            $model->logActivity('delete');
        });
    }

    public function logActivity(string $action, array $changes = [])
    {
        // Hanya log jika user sudah login
        if (!Auth::check()) {
            return;
        }

        // Siapkan deskripsi yang lebih informatif untuk produk
        $description = $this->getActivityDescription($action);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'changes' => $changes
        ]);
    }

    protected function getActivityDescription(string $action): string
    {
        $name = $this->name ?? $this->sku ?? $this->id;

        return match($action) {
            'create' => "Menambahkan Produk: {$name}",
            'update' => "Memperbarui Produk: {$name}",
            'delete' => "Menghapus Produk: {$name}",
            default => "{$action} Produk: {$name}"
        };
    }

    // Helper untuk mendapatkan perubahan yang signifikan
    public function getChanges(): array
    {
        $changes = [];
        $original = $this->getOriginal();
        $attributes = $this->getAttributes();

        // Daftar atribut yang ingin di-log perubahannya
        $trackAttributes = [
            'name', 
            'price', 
            'stock', 
            'minimum_stock', 
            'category_id', 
            'supplier_id', 
            'sku', 
            'purchase_price', 
            'sale_price'
        ];

        foreach ($trackAttributes as $key) {
            if (isset($attributes[$key]) && 
                (!isset($original[$key]) || $original[$key] != $attributes[$key])) {
                $changes[$key] = [
                    'old' => $original[$key] ?? null,
                    'new' => $attributes[$key]
                ];
            }
        }

        return $changes;
    }
}