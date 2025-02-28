<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SuppliersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('suppliers')->insert([
            [
                'name' => 'Supplier A',
                'contact' => '123456789',
                'address' => 'Jl. Example No. 1',
                'email' => 'suppliera@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Supplier B',
                'contact' => '987654321',
                'address' => 'Jl. Example No. 2',
                'email' => 'supplierb@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Tambahkan data lainnya di sini
        ]);
    }
}
