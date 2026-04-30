<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat Akun Administrator SEDEKAH
        User::create([
            'nama_user' => 'Admin SEDEKAH',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'administrator', // Sesuai revisi role enum administrator
        ]);

        // Membuat Akun Direktur SEDEKAH
        User::create([
            'nama_user' => 'Direktur SEDEKAH',
            'email' => 'direktur@gmail.com',
            'password' => Hash::make('direktur123'),
            'role' => 'direktur', // Sesuai revisi role enum direktur
        ]);
    }
}