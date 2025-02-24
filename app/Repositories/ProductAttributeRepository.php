<?php

namespace App\Repositories;

use App\Models\ProductAttribute;
use App\Interfaces\ProductAttributeRepositoryInterface;

class ProductAttributeRepository implements ProductAttributeRepositoryInterface
{
    public function getAllProductAttributes()
    {
        return ProductAttribute::all();
    }

    public function getProductAttributeById($productAttributeId)
    {
        return ProductAttribute::findOrFail($productAttributeId);
    }

    public function createProductAttribute(array $productAttributeDetails)
    {
        return ProductAttribute::create($productAttributeDetails);
    }

    public function updateProductAttribute($productAttributeId, array $newDetails)
    {
        return ProductAttribute::whereId($productAttributeId)->update($newDetails);
    }

    public function deleteProductAttribute($productAttributeId)
    {
        return ProductAttribute::destroy($productAttributeId);
    }
}
