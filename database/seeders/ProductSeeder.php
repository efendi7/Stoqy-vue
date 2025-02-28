<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        DB::table('products')->insert([
            [
                'name' => 'Laptop ASUS ROG',
                'sku' => 'LAP-001',
                'category_id' => 1, // Pastikan ID kategori sesuai
                'supplier_id' => 1, // Pastikan ID supplier sesuai
                'purchase_price' => 15000000,
                'sale_price' => 17500000,
                'stock' => 10,
                'minimum_stock' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Keyboard Mechanical RGB',
                'sku' => 'KEY-002',
                'category_id' => 2,
                'supplier_id' => 2,
                'purchase_price' => 500000,
                'sale_price' => 650000,
                'stock' => 25,
                'minimum_stock' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mouse Gaming Logitech',
                'sku' => 'MOU-003',
                'category_id' => 2,
                'supplier_id' => 2,
                'purchase_price' => 300000,
                'sale_price' => 450000,
                'stock' => 15,
                'minimum_stock' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
