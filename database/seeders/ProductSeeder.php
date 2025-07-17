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

        // Data produk (15 total)
        $products = [
            [
                'name' => 'Tepung Terigu Segitiga Biru 1kg',
                'sku' => 'SKU-TGB-001',
                'category_id' => 6,
                'supplier_id' => 6,
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
                'category_id' => 6,
                'supplier_id' => 7,
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
                'supplier_id' => 8,
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
                'category_id' => 9,
                'supplier_id' => 6,
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
            [
                'name' => 'Mie Instan Indomie Ayam Bawang',
                'sku' => 'SKU-MII-006',
                'category_id' => 6,
                'supplier_id' => 9,
                'initial_stock' => 300,
                'stock' => 300,
                'minimum_stock' => 30,
                'purchase_price' => 2500,
                'sale_price' => 3000,
                'image' => null,
            ],
            [
                'name' => 'Susu Kental Manis Indomilk 370g',
                'sku' => 'SKU-SKM-007',
                'category_id' => 7,
                'supplier_id' => 10,
                'initial_stock' => 80,
                'stock' => 80,
                'minimum_stock' => 10,
                'purchase_price' => 9000,
                'sale_price' => 11000,
                'image' => null,
            ],
            [
                'name' => 'Air Mineral Aqua 600ml',
                'sku' => 'SKU-AMA-008',
                'category_id' => 7,
                'supplier_id' => 11,
                'initial_stock' => 500,
                'stock' => 500,
                'minimum_stock' => 50,
                'purchase_price' => 2000,
                'sale_price' => 3000,
                'image' => null,
            ],
            [
                'name' => 'Kopi Kapal Api 165g',
                'sku' => 'SKU-KKA-009',
                'category_id' => 7,
                'supplier_id' => 9,
                'initial_stock' => 70,
                'stock' => 70,
                'minimum_stock' => 10,
                'purchase_price' => 13000,
                'sale_price' => 16000,
                'image' => null,
            ],
            [
                'name' => 'Pasta Gigi Pepsodent 190g',
                'sku' => 'SKU-PGP-010',
                'category_id' => 9,
                'supplier_id' => 10,
                'initial_stock' => 90,
                'stock' => 90,
                'minimum_stock' => 15,
                'purchase_price' => 12000,
                'sale_price' => 15000,
                'image' => null,
            ],
            [
                'name' => 'Sampo Lifebuoy 340ml',
                'sku' => 'SKU-SL-011',
                'category_id' => 9,
                'supplier_id' => 10,
                'initial_stock' => 100,
                'stock' => 100,
                'minimum_stock' => 20,
                'purchase_price' => 18000,
                'sale_price' => 22000,
                'image' => null,
            ],
            [
                'name' => 'Sabun Mandi Lux 75g',
                'sku' => 'SKU-SML-012',
                'category_id' => 9,
                'supplier_id' => 6,
                'initial_stock' => 150,
                'stock' => 150,
                'minimum_stock' => 25,
                'purchase_price' => 5000,
                'sale_price' => 6500,
                'image' => null,
            ],
            [
                'name' => 'Rokok Sampoerna Mild 16 batang',
                'sku' => 'SKU-RSM-013',
                'category_id' => 8,
                'supplier_id' => 12,
                'initial_stock' => 200,
                'stock' => 200,
                'minimum_stock' => 30,
                'purchase_price' => 29000,
                'sale_price' => 35000,
                'image' => null,
            ],
            [
                'name' => 'Tissue Paseo Soft Pack',
                'sku' => 'SKU-TPS-014',
                'category_id' => 9,
                'supplier_id' => 11,
                'initial_stock' => 80,
                'stock' => 80,
                'minimum_stock' => 10,
                'purchase_price' => 7000,
                'sale_price' => 10000,
                'image' => null,
            ],
            [
                'name' => 'Kecap Manis ABC 600ml',
                'sku' => 'SKU-KMA-015',
                'category_id' => 6,
                'supplier_id' => 7,
                'initial_stock' => 90,
                'stock' => 90,
                'minimum_stock' => 15,
                'purchase_price' => 12000,
                'sale_price' => 15000,
                'image' => null,
            ],
        ];

        // Masukkan data ke tabel products
        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
