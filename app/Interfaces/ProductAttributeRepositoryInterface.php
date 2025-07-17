<?php

namespace App\Interfaces;

use App\Models\ProductAttribute;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductAttributeRepositoryInterface
{
    public function getAllProductAttributes($search = null): LengthAwarePaginator;
    public function getProductAttributeById(int $productAttributeId): ProductAttribute;
    public function createProductAttribute(array $productAttributeDetails): ProductAttribute;
    public function updateProductAttribute(int $productAttributeId, array $newDetails): bool;
    public function deleteProductAttribute(int $productAttributeId): bool;
}