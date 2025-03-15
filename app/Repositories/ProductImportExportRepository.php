<?php

namespace App\Repositories;

use App\Interfaces\ProductImportExportRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductImportExportRepository implements ProductImportExportRepositoryInterface
{
    public function exportProducts()
    {
        try {
            $filename = 'products_export_' . date('Y-m-d_H-i-s') . '.csv';
            $path = 'public/exports/' . $filename;

            // Ambil data produk
            $products = Product::with(['category', 'supplier'])->get();

            // Buat file CSV
            $file = fopen(storage_path('app/' . $path), 'w');

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

            return Storage::url($path);
        } catch (\Exception $e) {
            Log::error('Error exporting products: ' . $e->getMessage());
            return null;
        }
    }

    public function exportTemplate()
    {
        try {
            $filename = 'products_import_template.csv';
            $path = 'public/exports/' . $filename;

            // Buat file CSV
            $file = fopen(storage_path('app/' . $path), 'w');

            // Header CSV
            fputcsv($file, ['Nama', 'SKU', 'Kategori ID', 'Supplier ID', 'Harga Beli', 'Harga Jual', 'Stok', 'Stok Minimum']);

            fclose($file);

            return Storage::url($path);
        } catch (\Exception $e) {
            Log::error('Error creating import template: ' . $e->getMessage());
            return null;
        }
    }

    public function importProducts($file)
    {
        try {
            // Validasi file
            $validator = Validator::make(['file' => $file], [
                'file' => 'required|mimes:csv,txt|max:10240',
            ]);

            if ($validator->fails()) {
                return ['error' => 'Format file tidak valid.'];
            }

            $filePath = $file->storeAs('temp', time() . '_' . $file->getClientOriginalName());
            $fullPath = storage_path('app/' . $filePath);

            if (!file_exists($fullPath)) {
                return ['error' => 'File tidak ditemukan.'];
            }

            // Buka file CSV
            $handle = fopen($fullPath, 'r');
            fgetcsv($handle); // Lewati header

            DB::beginTransaction();

            $imported = 0;
            $errors = [];
            $rowNumber = 1;

            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                $rowNumber++;

                if (count($row) < 8) {
                    $errors[] = "Baris $rowNumber memiliki kolom kurang dari 8.";
                    continue;
                }

                $productData = [
                    'name' => $row[0] ?? null,
                    'sku' => $row[1] ?? null,
                    'category_id' => $row[2] ?? null,
                    'supplier_id' => $row[3] ?? null,
                    'purchase_price' => is_numeric($row[4]) ? $row[4] : 0,
                    'sale_price' => is_numeric($row[5]) ? $row[5] : 0,
                    'stock' => is_numeric($row[6]) ? (int)$row[6] : 0,
                    'minimum_stock' => is_numeric($row[7]) ? (int)$row[7] : 0,
                ];

                $rowValidator = Validator::make($productData, [
                    'name' => 'required|string|max:255',
                    'sku' => 'required|string|unique:products,sku,NULL,id,deleted_at,NULL',
                    'category_id' => 'required|exists:categories,id',
                    'supplier_id' => 'nullable|exists:suppliers,id',
                    'purchase_price' => 'nullable|numeric|min:0',
                    'sale_price' => 'nullable|numeric|min:0',
                    'stock' => 'required|integer|min:0',
                    'minimum_stock' => 'required|integer|min:0',
                ]);

                if ($rowValidator->fails()) {
                    $errors[] = "Baris $rowNumber: " . implode(', ', $rowValidator->errors()->all());
                    continue;
                }

                Product::create($productData);
                $imported++;
            }

            fclose($handle);
            unlink($fullPath);

            DB::commit();

            return [
                'imported' => $imported,
                'errors' => $errors,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error importing products: ' . $e->getMessage());
            return ['error' => 'Gagal mengimpor data produk.'];
        }
    }
}
