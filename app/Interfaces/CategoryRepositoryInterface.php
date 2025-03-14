<?php
namespace App\Interfaces;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function getAllCategories();
    public function createCategory(array $data): Category;
    public function getCategoryById($id): Category;
    public function updateCategory($id, array $data): bool;
    public function deleteCategory($id): bool;
}
