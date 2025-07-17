<?php

namespace App\Repositories;

use App\Models\ProductAttribute;
use App\Interfaces\ProductAttributeRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class ProductAttributeRepository implements ProductAttributeRepositoryInterface
{
    /**
     * Get all product attributes with pagination and search functionality.
     */
    public function getAllProductAttributes($search = null): LengthAwarePaginator
    {
        $query = ProductAttribute::with('product:id,name')
            ->select('id', 'product_id', 'attribute_name', 'attribute_value');
        
        // Jika ada parameter search, lakukan pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('attribute_name', 'LIKE', "%{$search}%")
                  ->orWhere('attribute_value', 'LIKE', "%{$search}%")
                  ->orWhere('product_id', 'LIKE', "%{$search}%")
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        return $query->paginate(10);
    }

    /**
     * Get a product attribute by ID.
     */
    public function getProductAttributeById(int $productAttributeId): ProductAttribute
    {
        try {
            return ProductAttribute::findOrFail($productAttributeId);
        } catch (ModelNotFoundException $e) {
            throw new Exception("Product Attribute not found with ID: {$productAttributeId}");
        }
    }

    /**
     * Create a new product attribute.
     */
    public function createProductAttribute(array $productAttributeDetails): ProductAttribute
    {
        return ProductAttribute::create($productAttributeDetails);
    }

    /**
     * Update an existing product attribute.
     */
    public function updateProductAttribute(int $productAttributeId, array $newDetails): bool
    {
        $productAttribute = $this->getProductAttributeById($productAttributeId);
        return $productAttribute->update($newDetails);
    }

    /**
     * Delete a product attribute by ID.
     */
    public function deleteProductAttribute(int $productAttributeId): bool
    {
        $productAttribute = $this->getProductAttributeById($productAttributeId);
        return $productAttribute->delete();
    }
}