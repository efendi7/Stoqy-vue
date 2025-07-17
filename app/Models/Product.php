<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // <-- [1] BARIS BARU: Import class Storage

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'minimum_stock',
        'category_id',
        'supplier_id',
        'sku',
        'purchase_price',
        'sale_price',
        'image',
        'initial_stock'
    ];

    /**
     * [2] BARIS BARU: Memberitahu Laravel untuk selalu menyertakan
     * atribut 'image_url' saat model diubah menjadi array atau JSON.
     */
    protected $appends = ['image_url'];

    // Relasi ke kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relasi ke transaksi stok
    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    // Relasi ke atribut produk
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id');
    }

    // Relasi ke opname stok
    public function stockOpname()
    {
        return $this->hasOne(StockOpname::class);
    }

    /**
     * [3] FUNGSI BARU: Accessor untuk membuat atribut image_url.
     * Fungsi ini akan dipanggil secara otomatis oleh Laravel
     * untuk mendapatkan URL publik dari file gambar.
     *
     * @return string|null
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        return null;
    }
}