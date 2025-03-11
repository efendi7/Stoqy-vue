<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = $this->categoryService->createCategory($request->all());

        // Simpan log aktivitas
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role, 
            'action' => "Menambahkan kategori: {$category->name}",
            'properties' => json_encode([
                'category_id' => $category->id,
                'data' => $request->all(),
            ]),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Simpan data lama sebelum update
        $oldData = $category->toArray();
        $data = $request->except(['_token', '_method']);

        $this->categoryService->updateCategory($category->id, $data);

        // Simpan log aktivitas
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role, 
            'action' => "Mengedit kategori: {$category->name}",
            'properties' => json_encode([
                'before' => $oldData,
                'after' => $data,
            ]),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        // Simpan data kategori sebelum dihapus
        $categoryData = $category->toArray();

        $this->categoryService->deleteCategory($category->id);

        // Simpan log aktivitas
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role, 
            'action' => "Menghapus kategori: {$category->name}",
            'properties' => json_encode($categoryData),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
