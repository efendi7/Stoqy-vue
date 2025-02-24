<?php

namespace App\Services;

use App\Interfaces\ProductAttributeRepositoryInterface;
use Illuminate\Support\Collection;

class ProductAttributeService
{
    protected $productAttributeRepository;

    public function __construct(ProductAttributeRepositoryInterface $productAttributeRepository)
    {
        $this->productAttributeRepository = $productAttributeRepository;
    }


    public function getAllProductAttributes(): Collection
    {
        return $this->productAttributeRepository->getAllProductAttributes();
    }

    public function createProductAttribute(array $data)
    {
        return $this->productAttributeRepository->createProductAttribute($data);
    }

    public function updateProductAttribute($productAttributeId, array $data): bool
    {
        return $this->productAttributeRepository->updateProductAttribute($productAttributeId, $data);
    }

    public function deleteProductAttribute($productAttributeId): bool
    {
        return $this->productAttributeRepository->deleteProductAttribute($productAttributeId);
    }

    public function getAllProducts(): Collection
    {
        return $this->productAttributeRepository->getAllProducts();
    }

}
