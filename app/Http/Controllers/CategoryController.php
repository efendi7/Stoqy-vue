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
            'description' => 'nullable|string',  // Validasi deskripsi
        ]);

        $this->categoryService->createCategory($request->all());

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
        'description' => 'nullable|string',  // Validasi deskripsi
    ]);

    // Hanya kirim field yang diperlukan
    $data = $request->except(['_token', '_method']);

    $this->categoryService->updateCategory($category->id, $data);

    return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
}


    public function destroy(Category $category)
    {
        $this->categoryService->deleteCategory($category->id);
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
