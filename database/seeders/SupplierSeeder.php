<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('suppliers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 15 data supplier
        $suppliers = [
            [
                'name' => 'PT Indo Pangan',
                'email' => 'indopangan@example.com',
                'contact' => '02112345678',
                'address' => 'Jl. Merdeka No. 10, Jakarta',
            ],
            [
                'name' => 'PT Sinar Mas',
                'email' => 'sinarmas@example.com',
                'contact' => '02187654321',
                'address' => 'Jl. Sudirman No. 23, Jakarta',
            ],
            [
                'name' => 'CV Tani Makmur',
                'email' => 'tanimakmur@example.com',
                'contact' => '02223456789',
                'address' => 'Jl. Kebon Raya No. 5, Bandung',
            ],
            [
                'name' => 'UD Sumber Rejeki',
                'email' => 'sumberrejeki@example.com',
                'contact' => '0312345678',
                'address' => 'Jl. Diponegoro No. 12, Surabaya',
            ],
            [
                'name' => 'PT Agrindo Jaya',
                'email' => 'agrindojaya@example.com',
                'contact' => '0274123456',
                'address' => 'Jl. Malioboro No. 9, Yogyakarta',
            ],
            [
                'name' => 'CV Maju Bersama',
                'email' => 'majubersama@example.com',
                'contact' => '0361789456',
                'address' => 'Jl. Gatot Subroto No. 3, Denpasar',
            ],
            [
                'name' => 'PT Berkah Sentosa',
                'email' => 'berkahsentosa@example.com',
                'contact' => '0617894567',
                'address' => 'Jl. Sisingamangaraja No. 7, Medan',
            ],
            [
                'name' => 'UD Toko Murah',
                'email' => 'tokomurah@example.com',
                'contact' => '0241239876',
                'address' => 'Jl. Pandanaran No. 14, Semarang',
            ],
            [
                'name' => 'PT Alam Abadi',
                'email' => 'alamabadi@example.com',
                'contact' => '0751234567',
                'address' => 'Jl. Juanda No. 11, Padang',
            ],
            [
                'name' => 'CV Sejahtera Abadi',
                'email' => 'sejahteraabadi@example.com',
                'contact' => '0511987654',
                'address' => 'Jl. Ahmad Yani No. 8, Banjarmasin',
            ],
            [
                'name' => 'PT Nusantara Makmur',
                'email' => 'nusantaramakmur@example.com',
                'contact' => '0412345678',
                'address' => 'Jl. Ratulangi No. 2, Makassar',
            ],
            [
                'name' => 'CV Hasil Tani',
                'email' => 'hasiltani@example.com',
                'contact' => '0734567890',
                'address' => 'Jl. Letjen Suprapto No. 6, Bengkulu',
            ],
            [
                'name' => 'PT Buana Jaya',
                'email' => 'buanajaya@example.com',
                'contact' => '0771234567',
                'address' => 'Jl. Imam Bonjol No. 10, Batam',
            ],
            [
                'name' => 'CV Sumber Alam',
                'email' => 'sumberalam@example.com',
                'contact' => '0251234567',
                'address' => 'Jl. Pajajaran No. 4, Bogor',
            ],
            [
                'name' => 'UD Rezeki Lancar',
                'email' => 'rezekilancar@example.com',
                'contact' => '0281234567',
                'address' => 'Jl. Raya Barat No. 17, Tegal',
            ],
        ];

        DB::table('suppliers')->insert($suppliers);
    }
}
