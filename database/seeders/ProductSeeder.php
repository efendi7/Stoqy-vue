<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'category_id' => 1,
            'supplier_id' => 1,
            'name' => 'Laptop',
            'sku' => 'ELEC001',
            'purchase_price' => 5000000,
            'selling_price' => 6000000,
            'stock' => 10
        ]);
        Product::create([
            'category_id' => 2,
            'supplier_id' => 2,
            'name' => 'Kemeja',
            'sku' => 'CLOTH001',
            'purchase_price' => 100000,
            'selling_price' => 150000,
            'stock' => 20
        ]);
        Product::create([
            'category_id' => 3,
            'supplier_id' => 3,
            'name' => 'Biskuit',
            'sku' => 'FOOD001',
            'purchase_price' => 5000,
            'selling_price' => 8000,
            'stock' => 50
        ]);
    }
}
