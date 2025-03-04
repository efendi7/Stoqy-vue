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
        'stock',
        'image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function attributes()
{
    return $this->hasMany(ProductAttribute::class, 'product_id');
}

public function transactions()
{
    return $this->hasMany(StockTransaction::class);
}
}
