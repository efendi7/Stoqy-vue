<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        // Data supplier
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
        ];

        DB::table('suppliers')->insert($suppliers);
    }
}
