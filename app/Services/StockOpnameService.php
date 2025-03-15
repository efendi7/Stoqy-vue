<?php
namespace App\Services;

use App\Interfaces\StockOpnameRepositoryInterface;
use App\Models\ActivityLog;
use App\Models\Product;  // Ensure this is imported
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StockOpnameService
{
    protected $stockOpnameRepository;

    public function __construct(StockOpnameRepositoryInterface $stockOpnameRepository)
    {
        $this->stockOpnameRepository = $stockOpnameRepository;
    }

    public function validateStockOpnameData($data)
    {
        $validator = Validator::make($data, [
            'product_id' => 'required|exists:products,id',
            'recorded_stock' => 'required|integer|min:0',
            'actual_stock' => 'required|integer|min:0',
            'difference' => 'required|integer',
        ]);

        return $validator->validate(); // Akan otomatis throw error jika tidak valid
    }

    public function storeStockOpname($data)
    {
        // Fetch the product by ID
        $product = Product::find($data['product_id']); // Use product_id instead of expecting 'product' in the data

        // Check if the product exists
        if ($product) {
            // Store or update stock opname
            $stockOpname = $this->stockOpnameRepository->storeOrUpdateStockOpname($data);

            // Log the activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'role' => Auth::user()->role,
                'action' => "Melakukan stock opname untuk produk: {$product->name}", // Now using the fetched product object

                'properties' => json_encode([
                    'recorded_stock' => $data['recorded_stock'],
                    'actual_stock' => $data['actual_stock'],
                    'difference' => $data['difference'],
                ]),
            ]);

            return $stockOpname;
        }

        // Handle the case where the product was not found
        return null; // Or handle the error as needed
    }

    public function updateStockToActual($productId)
    {
        return $this->stockOpnameRepository->updateSystemStock($productId);
    }
}
