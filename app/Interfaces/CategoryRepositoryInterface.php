<?php

namespace App\Interfaces;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    // Ambil semua kategori dengan paginasi
    public function getAllCategories(): LengthAwarePaginator;

    // Ambil kategori berdasarkan ID
    public function getCategoryById($categoryId): Category;

    // Buat kategori baru
    public function createCategory(array $categoryDetails): Category;

    // Update kategori berdasarkan ID
    public function updateCategory($categoryId, array $newDetails): bool;

    // Hapus kategori berdasarkan ID
    public function deleteCategory($categoryId): bool;
}
