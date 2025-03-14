<?php
namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllCategories()
    {
        return Category::paginate(10);
    }

    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }

    public function getCategoryById($id): Category
    {
        return Category::findOrFail($id);
    }

    public function updateCategory($id, array $data): bool
    {
        $category = $this->getCategoryById($id);
        return $category->update($data);
    }

    public function deleteCategory($id): bool
    {
        $category = $this->getCategoryById($id);
        return $category->delete();
    }
}
