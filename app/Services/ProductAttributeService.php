<?php

namespace App\Services;

use App\Interfaces\{ProductAttributeRepositoryInterface, ProductRepositoryInterface};
use App\Models\ActivityLog;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\ProductAttribute;

class ProductAttributeService
{
    protected $productAttributeRepository;
    protected $productRepository;

    public function __construct(
        ProductAttributeRepositoryInterface $productAttributeRepository,
        ProductRepositoryInterface $productRepository
    ) {
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

    public function findProductAttributeById(int $id): ProductAttribute
    {
        return $this->productAttributeRepository->getProductAttributeById($id);
    }

    public function createProductAttribute(array $data): ProductAttribute
    {
        $attribute = $this->productAttributeRepository->createProductAttribute($data);

        $productName = $this->getProductNameById($data['product_id']);

        $this->logActivity('Menambahkan atribut', $attribute->attribute_name, $productName, [
            'attribute_id' => $attribute->id,
            'product_name' => $productName,
            'data' => $data,
        ]);

        return $attribute;
    }

    public function updateProductAttribute(int $id, array $data): bool
    {
        $attribute = $this->productAttributeRepository->getProductAttributeById($id);
        $oldData = $attribute->toArray();
        $productName = $this->getProductNameById($attribute->product_id);

        $updated = $this->productAttributeRepository->updateProductAttribute($id, $data);

        if ($updated) {
            $this->logActivity('Mengedit atribut', $attribute->attribute_name, $productName, [
                'product_name' => $productName,
                'before' => $oldData,
                'after' => $data,
            ]);
        }

        return $updated;
    }

    public function deleteProductAttribute(int $id): bool
    {
        $attribute = $this->productAttributeRepository->getProductAttributeById($id);
        $attributeData = $attribute->toArray();
        $productName = $this->getProductNameById($attribute->product_id);

        $deleted = $this->productAttributeRepository->deleteProductAttribute($id);

        if ($deleted) {
            $this->logActivity('Menghapus atribut', $attribute->attribute_name, $productName, [
                'product_name' => $productName,
                'deleted_data' => $attributeData,
            ]);
        }

        return $deleted;
    }

    private function getProductNameById(int $productId): string
    {
        $product = $this->productRepository->getProductById($productId);
        return $product ? $product->name : 'Unknown';
    }

    private function logActivity(string $action, string $attributeName, string $productName, array $properties): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role,
            'action' => "{$action} '{$attributeName}' untuk produk '{$productName}'",
            'properties' => json_encode($properties),
        ]);
    }

    public function validateProductAttributeData(array $data): array
    {
    return validator($data, [
        'attribute_name' => 'required|string|max:255',
        'attribute_value' => 'required|string|max:255',
        'product_id' => 'required|exists:products,id',
    ])->validate();
    }

}
