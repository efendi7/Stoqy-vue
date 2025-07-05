<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    // Menampilkan semua kategori
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

  public function store(Request $request)
{
    // Validasi data (Anda sudah mendelegasikannya ke service, yang mana itu bagus)
    // Kita tambahkan try-catch di sini untuk menangani ValidationException dari service Anda
    try {
        $validatedData = $this->categoryService->validateCategoryData($request->all());

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Jika validasi gagal, kembalikan error sebagai JSON
        return response()->json(['message' => $e->getMessage(), 'errors' => $e->errors()], 422);
    }

    // Buat kategori baru
    $this->categoryService->createCategory($validatedData);

    // KEMBALIKAN RESPON SUKSES SEBAGAI JSON
    return response()->json(['message' => 'Kategori berhasil ditambahkan!'], 201); // 201 = Created
}
    // Menampilkan form edit kategori
    public function edit($categoryId)
    {
        $category = $this->categoryService->getCategoryById($categoryId);
        return view('categories.edit', compact('category'));
    }

    // Memperbarui kategori
    public function update(Request $request, Category $category) // <-- Gunakan Route Model Binding (Category $category)
    {
        // 1. Validasi data yang masuk
        $validator = Validator::make($request->all(), [
            // Gunakan ID kategori untuk mengabaikan namanya sendiri saat validasi unique
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        // 2. Jika validasi gagal, kembalikan error dalam format JSON
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data yang diberikan tidak valid.',
                'errors' => $validator->errors()
            ], 422); // Kode status 422 untuk error validasi
        }

        // 3. Jika validasi berhasil, update kategori
        $category->update($validator->validated());

        // 4. Kembalikan respon sukses dalam format JSON
        return response()->json([
            'message' => 'Kategori berhasil diperbarui!'
        ], 200); // Kode status 200 OK
    }


    // Menghapus kategori
    public function destroy($categoryId)
    {
        $this->categoryService->deleteCategory($categoryId);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
