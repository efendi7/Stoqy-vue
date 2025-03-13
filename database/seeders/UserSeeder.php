<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Data pengguna awal
        $users = [
            [
                'name' => "Muhammad Ma'mun Efendi",
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'), // Password hash
                'role' => 'admin',
            ],
            [
                'name' => 'Ahmad Faisal',
                'email' => 'manager@example.com',
                'password' => Hash::make('password123'),
                'role' => 'warehouse_manager',
            ],
            [
                'name' => 'Siti Aisyah',
                'email' => 'staff@example.com',
                'password' => Hash::make('password123'),
                'role' => 'warehouse_staff',
            ],
        ];

        // Masukkan data ke tabel users
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
