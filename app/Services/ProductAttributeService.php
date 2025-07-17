<?php

namespace App\Services;

use App\Models\ProductAttribute;
use App\Interfaces\ProductAttributeRepositoryInterface;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductAttributeService
{
    protected ProductAttributeRepositoryInterface $productAttributeRepository;

    public function __construct(ProductAttributeRepositoryInterface $productAttributeRepository)
    {
        $this->productAttributeRepository = $productAttributeRepository;
    }

    public function getAllProductAttributes($search = null): LengthAwarePaginator
    {
        return $this->productAttributeRepository->getAllProductAttributes($search);
    }

    public function createProductAttribute($data)
    {
        return ProductAttribute::create($data);
    }

    public function updateProductAttribute($id, $data)
    {
        $productAttribute = ProductAttribute::findOrFail($id);
        $productAttribute->update($data);
        return $productAttribute;
    }

    public function deleteProductAttribute($id)
    {
        $productAttribute = ProductAttribute::findOrFail($id);
        $productAttribute->delete();
        return true;
    }

    public function validateProductAttributeData($data)
    {
        $validator = Validator::make($data, [
            'product_id' => 'required|exists:products,id',
            'attribute_name' => 'required|string|max:255',
            'attribute_value' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
