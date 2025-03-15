<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductImportExportController extends Controller
{
    /**
     * Show import/export form
     */
    public function index()
    {
        return view('products.import-export');
    }

    /**
     * Export products to Excel file
     */
    public function export()
    {
        try {
            $filename = 'products_export_' . date('Y-m-d_H-i-s') . '.csv';
            $path = storage_path('app/public/exports/' . $filename);
    
            // Pastikan folder ada
            if (!file_exists(storage_path('app/public/exports'))) {
                mkdir(storage_path('app/public/exports'), 0755, true);
            }
    
            // Ambil data produk
            $products = Product::with(['category', 'supplier'])->get();
    
            // Buat file CSV
            $file = fopen($path, 'w');
            
            // Header CSV
            fputcsv($file, ['ID', 'Nama', 'SKU', 'Kategori', 'Supplier', 'Harga Beli', 'Harga Jual', 'Stok', 'Stok Minimum', 'Dibuat Pada']);
            
            // Isi data produk
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->sku,
                    $product->category ? $product->category->name : 'N/A',
                    $product->supplier ? $product->supplier->name : 'N/A',
                    $product->purchase_price,
                    $product->sale_price,
                    $product->stock,
                    $product->minimum_stock,
                    $product->created_at,
                ]);
            }
    
            fclose($file);
    
            // Return file untuk di-download
            return response()->download($path)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Error exporting products: ' . $e->getMessage());
            return redirect()->route('products.import-export.index')
                ->with('error', 'Gagal mengekspor data produk: ' . $e->getMessage());
        }
    }
    

    /**
     * Export template for importing products
     */
    public function exportTemplate()
    {
        try {
            $filename = 'products_import_template.csv';
            $path = storage_path('app/public/exports/' . $filename);
    
            // Pastikan folder ada
            if (!file_exists(storage_path('app/public/exports'))) {
                mkdir(storage_path('app/public/exports'), 0755, true);
            }
    
            // Buat file CSV
            $file = fopen($path, 'w');
            
            // Header CSV
            fputcsv($file, ['Nama', 'SKU', 'Kategori ID', 'Supplier ID', 'Harga Beli', 'Harga Jual', 'Stok', 'Stok Minimum']);
            
            fclose($file);
    
            // Return file untuk di-download
            return response()->download($path)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Error creating import template: ' . $e->getMessage());
            return redirect()->route('products.import-export.index')
                ->with('error', 'Gagal membuat template impor: ' . $e->getMessage());
        }
    }
    

    /**
     * Import products from Excel file
     */
    public function import(Request $request)
    {
        try {
            // Validasi file
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:csv,txt|max:10240', // Hanya terima CSV
            ]);
    
            if ($validator->fails()) {
                return redirect()->route('products.import-export.index')
                    ->withErrors($validator)
                    ->with('error', 'Format file tidak valid.');
            }
    
            // Simpan file sementara
            $file = $request->file('file');
            $path = $file->store('temp');
            $fullPath = storage_path('app/' . $path);
    
            // Buka file CSV
            $fileHandle = fopen($fullPath, 'r');
            if ($fileHandle === false) {
                throw new \Exception("Gagal membuka file CSV.");
            }
    
            // Lewati baris header
            fgetcsv($fileHandle);
    
            // Inisialisasi counter
            $imported = 0;
            $errors = 0;
            $errorMessages = [];
    
            // Baca tiap baris
            while (($row = fgetcsv($fileHandle, 1000, ",")) !== false) {
                if (empty(array_filter($row))) {
                    continue; // Skip baris kosong
                }
    
                // Ekstrak data
                $productData = [
                    'name' => $row[0] ?? null,
                    'sku' => $row[1] ?? null,
                    'category_id' => $row[2] ?? null,
                    'supplier_id' => $row[3] ?? null,
                    'purchase_price' => $row[4] ?? 0,
                    'sale_price' => $row[5] ?? 0,
                    'stock' => $row[6] ?? 0,
                    'minimum_stock' => $row[7] ?? 0,
                ];
    
                // Validasi data
                $rowValidator = Validator::make($productData, [
                    'name' => 'required|string|max:255',
                    'sku' => 'required|string|unique:products,sku,NULL,id,deleted_at,NULL',
                    'category_id' => 'required|exists:categories,id',
                    'supplier_id' => 'nullable|exists:suppliers,id',
                    'purchase_price' => 'nullable|numeric',
                    'sale_price' => 'nullable|numeric',
                    'stock' => 'required|integer',
                    'minimum_stock' => 'required|integer|min:0',
                ]);
    
                if ($rowValidator->fails()) {
                    $errors++;
                    $errorMessages[] = "Baris " . ($imported + $errors + 2) . ": " . implode(', ', $rowValidator->errors()->all());
                    continue;
                }
    
                // Simpan produk ke database
                Product::create($productData);
                $imported++;
            }
    
            fclose($fileHandle);
            unlink($fullPath); // Hapus file sementara
    
            // Pesan hasil impor
            $message = "Impor produk selesai. $imported produk berhasil diimpor.";
            if ($errors > 0) {
                $message .= " $errors produk gagal diimpor.";
            }
    
            return redirect()->route('products.import-export.index')
                ->with('success', $message)
                ->with('errorMessages', $errorMessages);
            
        } catch (\Exception $e) {
            Log::error('Error importing products: ' . $e->getMessage());
            return redirect()->route('products.import-export.index')
                ->with('error', 'Gagal mengimpor data produk: ' . $e->getMessage());
        }
    }
    
}