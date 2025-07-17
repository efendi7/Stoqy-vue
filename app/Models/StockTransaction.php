<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'minimum_stock',
        'status',
        'notes', // Catatan awal saat transaksi dibuat
        'transaction_date',
        'note', // Catatan tambahan saat konfirmasi/penolakan
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'Pending', // Default status transaksi
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    /**
     * Menerapkan filter pencarian pada query.
     * Method ini akan dipanggil saat Anda menggunakan ->filter() di Controller.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, array $filters)
    {
        // Cek jika ada filter 'search' dan tidak kosong
        if ($filters['search'] ?? false) {
            $searchTerm = '%' . $filters['search'] . '%';

            $query->where(function ($subQuery) use ($searchTerm) {
                // Cari berdasarkan nama produk melalui relasi 'product'
                $subQuery->whereHas('product', function ($productQuery) use ($searchTerm) {
                    $productQuery->where('name', 'like', $searchTerm);
                })
                // Atau cari berdasarkan jenis transaksi
                ->orWhere('type', 'like', $searchTerm);
            });
        }
    }

    /**
     * Mendefinisikan relasi ke model Product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Mendefinisikan relasi ke model User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendefinisikan relasi ke model Item (jika ada).
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'stock_transaction_id'); 
    }

    /**
     * Mendefinisikan relasi ke model TransactionActivity.
     * Satu transaksi bisa memiliki banyak log aktivitas.
     */
    public function activities(): HasMany
    {
        // Assuming your activity log model is named TransactionActivity
        // This defines the one-to-many relationship.
        // ->latest() will order the activities by the newest first.
        return $this->hasMany(TransactionActivity::class)->latest();
    }
}
