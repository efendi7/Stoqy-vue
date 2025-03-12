<?php

namespace App\Repositories;

use App\Models\Category;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryRepositoryInterface
{
    // Ambil semua kategori dengan paginasi
    public function getAllCategories(): LengthAwarePaginator
    {
        return Category::paginate(10);
    }

    // Ambil kategori berdasarkan ID
    public function getCategoryById($categoryId): Category
    {
        return Category::findOrFail($categoryId);
    }

    // Buat kategori baru
    public function createCategory(array $categoryDetails): Category
    {
        return Category::create($categoryDetails);
    }

    // Update kategori berdasarkan ID
    public function updateCategory($categoryId, array $newDetails): bool
    {
        $category = Category::findOrFail($categoryId);
        return $category->update($newDetails);
    }

    // Hapus kategori berdasarkan ID
    public function deleteCategory($categoryId): bool
    {
        $category = Category::findOrFail($categoryId);
        return $category->delete();
    }
}
