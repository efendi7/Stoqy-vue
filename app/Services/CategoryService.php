<?php

namespace App\Services;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    // Ambil semua kategori
    public function getAllCategories(): LengthAwarePaginator
    {
        return $this->categoryRepository->getAllCategories();
    }

    public function getCategoryById($categoryId)
{
    return Category::findOrFail($categoryId);
}


    // Buat kategori baru dan catat aktivitas
    public function createCategory(array $data): Category
    {
        $category = $this->categoryRepository->createCategory($data);
        $this->logActivity("Menambahkan kategori: {$category->name}", $category, ['data' => $data]);

        return $category;
    }

    // Update kategori dan catat aktivitas
    public function updateCategory($categoryId, array $data): bool
    {
        $category = $this->categoryRepository->getCategoryById($categoryId);
        $oldData = $category->toArray();

        $updated = $this->categoryRepository->updateCategory($categoryId, $data);
        if ($updated) {
            $this->logActivity("Mengedit kategori: {$category->name}", $category, [
                'before' => $oldData,
                'after' => $data,
            ]);
        }

        return $updated;
    }

    // Hapus kategori dan catat aktivitas
    public function deleteCategory($categoryId): bool
    {
        $category = $this->categoryRepository->getCategoryById($categoryId);
        $deleted = $this->categoryRepository->deleteCategory($categoryId);
        if ($deleted) {
            $this->logActivity("Menghapus kategori: {$category->name}", $category, $category->toArray());
        }

        return $deleted;
    }

    // Catat aktivitas pengguna
    private function logActivity($action, Category $category, array $properties = [])
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role,
            'action' => $action,
            'properties' => json_encode($properties),
        ]);
    }

    public function validateCategoryData(array $data): array
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ])->validate();
    }
}
