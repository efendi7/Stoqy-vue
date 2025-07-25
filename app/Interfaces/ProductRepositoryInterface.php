<?php

namespace App\Interfaces;

interface ProductRepositoryInterface
{
    public function getAllProducts();
    public function findById($id); // Tambahkan ini biar konsisten
    public function getProductById($id);
    public function createProduct(array $data);
    public function updateProduct($id, array $data);
    public function deleteProduct($id);
    public function searchProducts($search, $status, $perPage);
    // public function getCategories();
    // public function getSuppliers();
}
