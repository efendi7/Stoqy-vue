<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Definisikan relasi dengan stock_transactions
    public function stockTransactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class, 'user_id', 'id');
    }

    // Soft delete cascading
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            $user->stockTransactions()->delete();
        });
    }
}