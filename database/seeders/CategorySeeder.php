<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Data kategori (15 total)
        $categories = [
            ['name' => 'Bahan Pokok', 'description' => 'Produk utama seperti beras, gula, dan minyak goreng.'],
            ['name' => 'Minuman', 'description' => 'Minuman seperti teh, kopi, dan jus.'],
            ['name' => 'Makanan Ringan', 'description' => 'Camilan seperti biskuit dan keripik.'],
            ['name' => 'Produk Kebersihan', 'description' => 'Sabun, deterjen, dan produk pembersih lainnya.'],
            ['name' => 'Perlengkapan Dapur', 'description' => 'Bumbu masak dan minyak zaitun.'],
            ['name' => 'Produk Bayi', 'description' => 'Popok, susu formula, dan perlengkapan bayi lainnya.'],
            ['name' => 'Produk Kecantikan', 'description' => 'Kosmetik dan skincare seperti lotion dan facial wash.'],
            ['name' => 'Alat Tulis Kantor', 'description' => 'Pulpen, kertas, dan perlengkapan kantor lainnya.'],
            ['name' => 'Elektronik Rumah Tangga', 'description' => 'Peralatan dapur seperti blender, rice cooker, dll.'],
            ['name' => 'Pakaian & Aksesori', 'description' => 'Kaos, celana, topi, dan aksesori lainnya.'],
            ['name' => 'Produk Kesehatan', 'description' => 'Vitamin, obat-obatan ringan, dan alat kesehatan.'],
            ['name' => 'Makanan Beku', 'description' => 'Nugget, sosis, dan makanan beku lainnya.'],
            ['name' => 'Makanan Instan', 'description' => 'Mie instan, bubur instan, dan produk sejenis.'],
            ['name' => 'Peralatan Kebun', 'description' => 'Alat berkebun seperti cangkul, pot, dan pupuk.'],
            ['name' => 'Produk Rokok & Tembakau', 'description' => 'Rokok, tembakau linting, dan korek api.'],
        ];

        DB::table('categories')->insert($categories);
    }
}
