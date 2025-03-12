<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    // Tampilkan semua kategori
    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('categories.index', compact('categories'));
    }

    // Form tambah kategori
    public function create()
    {
        return view('categories.create');
    }

    // Simpan kategori baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $this->categoryService->createCategory($validated);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    // Form edit kategori
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // Perbarui kategori
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $this->categoryService->updateCategory($category->id, $validated);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    // Hapus kategori
    public function destroy(Category $category)
    {
        $this->categoryService->deleteCategory($category->id);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
