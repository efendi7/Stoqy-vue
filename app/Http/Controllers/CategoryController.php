<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest; // Kita akan gunakan Form Request
use App\Http\Requests\UpdateCategoryRequest; // Kita akan gunakan Form Request
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia; // <-- WAJIB: Import Inertia
use Illuminate\Support\Facades\Redirect; // <-- WAJIB: Import Redirect

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori dengan filter dan pagination.
     */
    public function index(Request $request)
    {
        // Ambil query pencarian dari request
        $filters = $request->only('search');

        $categories = Category::query()
            ->when($request->input('search'), function ($query, $search) {
                // Jika ada keyword pencarian, filter berdasarkan nama atau deskripsi
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest() // Urutkan dari yang terbaru
            ->paginate(10) // Gunakan pagination
            ->withQueryString(); // Agar parameter filter tetap ada di link pagination

        // Kembalikan komponen Vue dengan data yang diperlukan sebagai props
        return Inertia::render('Categories/Index', [
            'categories' => $categories,
            'filters' => $filters,
            'flash' => [
                'success' => session('success'),
            ],
        ]);
    }

    /**
     * Menyimpan kategori baru ke database.
     * Menggunakan StoreCategoryRequest untuk validasi.
     */
    public function store(StoreCategoryRequest $request)
    {
        // Validasi sudah otomatis ditangani oleh StoreCategoryRequest.
        // Jika validasi gagal, Inertia akan otomatis mengembalikan error ke form.
        
        // Ambil data yang sudah tervalidasi
        $validatedData = $request->validated();

        // Buat kategori baru
        Category::create($validatedData);

        // Alihkan kembali ke halaman index dengan pesan sukses
        return Redirect::route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Memperbarui kategori yang ada.
     * Menggunakan UpdateCategoryRequest untuk validasi.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // Validasi sudah otomatis ditangani oleh UpdateCategoryRequest.
        
        // Ambil data yang sudah tervalidasi
        $validatedData = $request->validated();
        
        // Update kategori
        $category->update($validatedData);

        // Alihkan kembali ke halaman index dengan pesan sukses
        return Redirect::route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Menghapus kategori.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        // Alihkan kembali ke halaman index dengan pesan sukses
        return Redirect::route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }

    /*
    |--------------------------------------------------------------------------
    | METHOD `create()` dan `edit()` TIDAK DIPERLUKAN
    |--------------------------------------------------------------------------
    |
    | Dalam aplikasi Inertia/Vue modern, form untuk "tambah" dan "edit"
    | biasanya ditampilkan dalam bentuk MODAL di halaman index utama.
    | Oleh karena itu, kita tidak lagi memerlukan method terpisah untuk
    | hanya menampilkan halaman form (return view/render).
    |
    */
}