<?php

namespace App\Interfaces;

interface ProductAttributeRepositoryInterface
{
    public function getAllProductAttributes();
    public function getProductAttributeById($productAttributeId);
    public function createProductAttribute(array $productAttributeDetails);
    public function updateProductAttribute($productAttributeId, array $newDetails);
    public function deleteProductAttribute($productAttributeId);
}
