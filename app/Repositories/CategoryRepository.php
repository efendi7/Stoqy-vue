<?php

namespace App\Repositories;

use App\Models\Category;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllCategories(): LengthAwarePaginator
    {
        return Category::paginate(10);
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
