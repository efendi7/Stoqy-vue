<?php

namespace App\Services;

use App\Interfaces\{ProductAttributeRepositoryInterface, ProductRepositoryInterface};
use Illuminate\Pagination\LengthAwarePaginator;

class ProductAttributeService
{
    protected $productAttributeRepository;
    protected $productRepository;

    public function __construct(ProductAttributeRepositoryInterface $productAttributeRepository, ProductRepositoryInterface $productRepository)
    {
        $this->productAttributeRepository = $productAttributeRepository;
        $this->productRepository = $productRepository;
    }

    public function getAllProductAttributes(): LengthAwarePaginator
    {
        return $this->productAttributeRepository->getAllProductAttributes();
    }

    public function getAllProducts(): LengthAwarePaginator
    {
        return $this->productRepository->getAllProducts();
    }

    public function findProductAttributeById($id)
    {
        return $this->productAttributeRepository->getProductAttributeById($id);
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
}
