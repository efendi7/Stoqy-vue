<?php

namespace App\Interfaces;

use App\Models\ProductAttribute;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductAttributeRepositoryInterface
{
    /**
     * Get all product attributes with pagination.
     */
    public function getAllProductAttributes(): LengthAwarePaginator;

    /**
     * Get a product attribute by ID.
     */
    public function getProductAttributeById(int $productAttributeId): ProductAttribute;

    /**
     * Create a new product attribute.
     */
    public function createProductAttribute(array $productAttributeDetails): ProductAttribute;

    /**
     * Update an existing product attribute.
     */
    public function updateProductAttribute(int $productAttributeId, array $newDetails): bool;

    /**
     * Delete a product attribute by ID.
     */
    public function deleteProductAttribute(int $productAttributeId): bool;
}
