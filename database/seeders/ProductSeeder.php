<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Hapus data sebelumnya
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data produk spesifik
        $products = [
            [
                'name' => 'Tepung Terigu Segitiga Biru 1kg',
                'sku' => 'SKU-TGB-001',
                'category_id' => 6, // ID kategori disesuaikan
                'supplier_id' => 6, // ID supplier disesuaikan
                'initial_stock' => 100,
                'stock' => 100,
                'minimum_stock' => 10,
                'purchase_price' => 12000,
                'sale_price' => 15000,
                'image' => null,
            ],
            [
                'name' => 'Minyak Goreng Filma 2L',
                'sku' => 'SKU-MGF-002',
                'category_id' => 6, // ID kategori disesuaikan
                'supplier_id' => 7, // ID supplier disesuaikan
                'initial_stock' => 50,
                'stock' => 50,
                'minimum_stock' => 5,
                'purchase_price' => 25000,
                'sale_price' => 30000,
                'image' => null,
            ],
            [
                'name' => 'Beras Pandan Wangi 5kg',
                'sku' => 'SKU-BPW-003',
                'category_id' => 6,
                'supplier_id' => 8, // ID supplier disesuaikan
                'initial_stock' => 200,
                'stock' => 200,
                'minimum_stock' => 20,
                'purchase_price' => 55000,
                'sale_price' => 65000,
                'image' => null,
            ],
            [
                'name' => 'Sabun Cuci Piring Sunlight 400ml',
                'sku' => 'SKU-SCP-004',
                'category_id' => 9, // ID kategori disesuaikan
                'supplier_id' => 6, // ID supplier disesuaikan
                'initial_stock' => 120,
                'stock' => 120,
                'minimum_stock' => 15,
                'purchase_price' => 8000,
                'sale_price' => 11000,
                'image' => null,
            ],
            [
                'name' => 'Gula Pasir Gulaku 1kg',
                'sku' => 'SKU-GPG-005',
                'category_id' => 6,
                'supplier_id' => 6,
                'initial_stock' => 150,
                'stock' => 150,
                'minimum_stock' => 20,
                'purchase_price' => 14000,
                'sale_price' => 18000,
                'image' => null,
            ],
        ];

        // Masukkan data ke tabel products
        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
