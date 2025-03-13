<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Data kategori
        $categories = [
            ['name' => 'Bahan Pokok', 'description' => 'Produk utama seperti beras, gula, dan minyak goreng.'],
            ['name' => 'Minuman', 'description' => 'Minuman seperti teh, kopi, dan jus.'],
            ['name' => 'Makanan Ringan', 'description' => 'Camilan seperti biskuit dan keripik.'],
            ['name' => 'Produk Kebersihan', 'description' => 'Sabun, deterjen, dan produk pembersih lainnya.'],
            ['name' => 'Perlengkapan Dapur', 'description' => 'Bumbu masak dan minyak zaitun.'],
        ];

        DB::table('categories')->insert($categories);
    }
}
