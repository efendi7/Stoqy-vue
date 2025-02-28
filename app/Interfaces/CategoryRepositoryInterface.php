<?php

namespace App\Interfaces;
use Illuminate\Pagination\LengthAwarePaginator;
interface CategoryRepositoryInterface
{
    public function getAllCategories(): LengthAwarePaginator;
    public function getCategoryById($categoryId);
    public function createCategory(array $categoryDetails);
    public function updateCategory($categoryId, array $newDetails);
    public function deleteCategory($categoryId);
}
