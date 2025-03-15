<?php

namespace App\Http\Controllers;

use App\Services\ProductImportExportService;
use Illuminate\Http\Request;

class ProductImportExportController extends Controller
{
    protected $service;

    public function __construct(ProductImportExportService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('products.import-export');
    }

    public function export()
    {
        $fileUrl = $this->service->exportProducts();

        if (!$fileUrl) {
            return redirect()->route('products.import-export.index')
                ->with('error', 'Gagal mengekspor data produk.');
        }

        return response()->download(storage_path('app/' . str_replace('/storage', 'public', $fileUrl)));
    }

    public function exportTemplate()
    {
        $fileUrl = $this->service->exportTemplate();

        if (!$fileUrl) {
            return redirect()->route('products.import-export.index')
                ->with('error', 'Gagal membuat template impor.');
        }

        return response()->download(storage_path('app/' . str_replace('/storage', 'public', $fileUrl)));
    }

    public function import(Request $request)
    {
        $result = $this->service->importProducts($request->file('file'));

        return redirect()->route('products.import-export.index')
            ->with('success', "Produk berhasil diimpor: " . ($result['imported'] ?? 0))
            ->with('errorMessages', $result['errors'] ?? []);
    }
}
