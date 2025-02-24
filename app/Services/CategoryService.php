<?php

namespace App\Services;

use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Collection;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    public function getAllCategories(): Collection
    {
        return $this->categoryRepository->getAllCategories();
    }

    public function createCategory(array $data)
    {
        return $this->categoryRepository->createCategory($data);
    }

    public function updateCategory($categoryId, array $data): bool
    {
        return $this->categoryRepository->updateCategory($categoryId, $data);
    }

    public function deleteCategory($categoryId): bool
    {
        return $this->categoryRepository->deleteCategory($categoryId);
    }

}
