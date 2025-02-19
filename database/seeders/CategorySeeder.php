<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['name' => 'Elektronik', 'description' => 'Kategori untuk produk elektronik']);
        Category::create(['name' => 'Pakaian', 'description' => 'Kategori untuk produk pakaian']);
        Category::create(['name' => 'Makanan', 'description' => 'Kategori untuk produk makanan']);
    }
}
