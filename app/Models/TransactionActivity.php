<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionActivity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'stock_transaction_id',
        'user_id',
        'activity',
        'notes',
    ];

    /**
     * Mendefinisikan relasi ke model StockTransaction.
     * Setiap log aktivitas dimiliki oleh satu transaksi.
     */
    public function stockTransaction(): BelongsTo
    {
        return $this->belongsTo(StockTransaction::class);
    }

    /**
     * Mendefinisikan relasi ke model User.
     * Setiap log aktivitas dilakukan oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
