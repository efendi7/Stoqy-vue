<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
