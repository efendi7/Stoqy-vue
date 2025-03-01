<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'actual_stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
