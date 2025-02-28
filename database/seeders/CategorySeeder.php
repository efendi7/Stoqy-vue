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
        Category::create(['name' => 'Laptop', 'description' => 'Kategori untuk produk laptop']);
        Category::create(['name' => 'Keyboard', 'description' => 'Kategori untuk produk keyboard']);
        Category::create(['name' => 'Mouse', 'description' => 'Kategori untuk produk mouse']);
        Category::create(['name' => 'Monitor', 'description' => 'Kategori untuk produk monitor']);
        Category::create(['name' => 'Aksesoris', 'description' => 'Kategori untuk produk aksesoris komputer']);
    }
}
