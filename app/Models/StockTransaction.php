<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'minimum_stock',
        'status',
        'notes',
        'transaction_date',
        'note' // Pastikan menggunakan snake_case
    ];

    protected $attributes = [
        'status' => 'Pending' // Default status transaksi
    ];
    
    protected $dates = [
        'transaction_date',
        // atribut lainnya
    ];

    // Relasi ke model Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
