<?php

namespace App\Repositories;

use App\Models\StockOpname;
use App\Models\Product;
use App\Interfaces\StockOpnameRepositoryInterface;

class StockOpnameRepository implements StockOpnameRepositoryInterface
{
    public function getAllStockOpnames()
    {
        return StockOpname::with('product')->get();
    }

    public function findStockOpnameByProductId($productId)
    {
        return StockOpname::where('product_id', $productId)->first();
    }

    public function storeOrUpdateStockOpname($data)
    {
        $product = Product::findOrFail($data['product_id']);

        return StockOpname::updateOrCreate(
            ['product_id' => $product->id],
            [
                'recorded_stock' => $data['recorded_stock'],
                'actual_stock' => $data['actual_stock'],
                'difference' => $data['difference'],
                'updated_at' => now(),
            ]
        );
    }
    public function updateSystemStock($productId)
{
    $stockOpname = StockOpname::where('product_id', $productId)->first();

    if ($stockOpname) {
        $product = Product::find($productId);
        if ($product) {
            $product->stock = $stockOpname->actual_stock; // Update stok sesuai stok fisik
            $product->save();

            return true;
        }
    }
    return false;
}

}
