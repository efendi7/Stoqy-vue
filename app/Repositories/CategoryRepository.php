<?php

namespace App\Repositories;

use App\Models\Category;
use App\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllCategories()
    {
        return Category::all();
    }

    public function getCategoryById($categoryId)
    {
        return Category::findOrFail($categoryId);
    }

    public function createCategory(array $categoryDetails)
    {
        return Category::create($categoryDetails);
    }

    public function updateCategory($categoryId, array $newDetails)
    {
        return Category::whereId($categoryId)->update($newDetails);
    }

    public function deleteCategory($categoryId)
    {
        return Category::destroy($categoryId);
    }
}
