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
            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set column headers
            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Nama');
            $sheet->setCellValue('C1', 'SKU');
            $sheet->setCellValue('D1', 'Kategori');
            $sheet->setCellValue('E1', 'Supplier');
            $sheet->setCellValue('F1', 'Harga Beli');
            $sheet->setCellValue('G1', 'Harga Jual');
            $sheet->setCellValue('H1', 'Stok');
            $sheet->setCellValue('I1', 'Stok Minimum');
            $sheet->setCellValue('J1', 'Dibuat Pada');
            
            // Get all products
            $products = Product::with(['category', 'supplier'])->get();
            
            // Fill data rows
            $row = 2;
            foreach ($products as $product) {
                $sheet->setCellValue('A' . $row, $product->id);
                $sheet->setCellValue('B' . $row, $product->name);
                $sheet->setCellValue('C' . $row, $product->sku);
                $sheet->setCellValue('D' . $row, $product->category ? $product->category->name : 'N/A');
                $sheet->setCellValue('E' . $row, $product->supplier ? $product->supplier->name : 'N/A');
                $sheet->setCellValue('F' . $row, $product->purchase_price);
                $sheet->setCellValue('G' . $row, $product->sale_price);
                $sheet->setCellValue('H' . $row, $product->stock);
                $sheet->setCellValue('I' . $row, $product->minimum_stock);
                $sheet->setCellValue('J' . $row, $product->created_at);
                $row++;
            }
            
            // Auto size columns
            foreach(range('A','J') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
            
            // Create file
            $writer = new Xlsx($spreadsheet);
            $filename = 'products_export_' . date('Y-m-d_H-i-s') . '.xlsx';
            $path = storage_path('app/public/exports/' . $filename);
            
            // Make sure the directory exists
            if (!file_exists(storage_path('app/public/exports'))) {
                mkdir(storage_path('app/public/exports'), 0755, true);
            }
            
            // Save file
            $writer->save($path);
            
            // Return download response
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
            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set column headers
            $sheet->setCellValue('A1', 'Nama');
            $sheet->setCellValue('B1', 'SKU');
            $sheet->setCellValue('C1', 'Kategori ID');
            $sheet->setCellValue('D1', 'Supplier ID');
            $sheet->setCellValue('E1', 'Harga Beli');
            $sheet->setCellValue('F1', 'Harga Jual');
            $sheet->setCellValue('G1', 'Stok');
            $sheet->setCellValue('H1', 'Stok Minimum');
            
            // Auto size columns
            foreach(range('A','H') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
            
            // Create file
            $writer = new Xlsx($spreadsheet);
            $filename = 'products_import_template.xlsx';
            $path = storage_path('app/public/exports/' . $filename);
            
            // Make sure the directory exists
            if (!file_exists(storage_path('app/public/exports'))) {
                mkdir(storage_path('app/public/exports'), 0755, true);
            }
            
            // Save file
            $writer->save($path);
            
            // Return download response
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
            // Validate file
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:xlsx,xls|max:10240', // 10MB max
            ]);
            
            if ($validator->fails()) {
                return redirect()->route('products.import-export.index')
                    ->withErrors($validator)
                    ->with('error', 'Format file tidak valid.');
            }
            
            // Get file and store it
            $file = $request->file('file');
            $path = $file->store('temp');
            $fullPath = storage_path('app/' . $path);
            
            // Load the Excel file
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fullPath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($fullPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Remove header row
            array_shift($rows);
            
            // Initialize counters
            $imported = 0;
            $errors = 0;
            $errorMessages = [];
            
            // Process each row
            foreach ($rows as $rowIndex => $row) {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                // Extract data
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
                
                // Validate data
                $rowValidator = Validator::make($productData, [
                    'name' => 'required|string|max:255',
                    'sku' => 'required|string|unique:products,sku',
                    'category_id' => 'required|exists:categories,id',
                    'supplier_id' => 'nullable|exists:suppliers,id',
                    'purchase_price' => 'nullable|numeric',
                    'sale_price' => 'nullable|numeric',
                    'stock' => 'required|integer',
                    'minimum_stock' => 'required|integer|min:0',
                ]);
                
                if ($rowValidator->fails()) {
                    $errors++;
                    $errorMessages[] = "Baris " . ($rowIndex + 2) . ": " . implode(', ', $rowValidator->errors()->all());
                    continue;
                }
                
                // Create product
                Product::create($productData);
                $imported++;
            }
            
            // Clean up
            unlink($fullPath);
            
            // Prepare response message
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