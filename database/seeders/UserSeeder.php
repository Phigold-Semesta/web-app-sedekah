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
        // --- 1. AKUN UTAMA (Bawaan Sebelumnya) ---
       
        // --- 2. TAMBAHAN 6 USER BARU ---

        // 3 User Administrator Tambahan
        User::create([
            'nama_user' => ' Abdul Rohman',
            'username' => 'aduladmin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'administrator',
        ]);

        User::create([
            'nama_user' => 'Staff Ops 1',
            'username' => 'ops1@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'administrator',
        ]);

        User::create([
            'nama_user' => 'Staff Ops 2',
            'username' => 'ops2@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'administrator',
        ]);

        // 3 User Direktur Tambahan
        User::create([
            'nama_user' => 'Bapak Budi Direktur',
            'username' => 'budi@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'direktur',
        ]);

        User::create([
            'nama_user' => 'Ibu Siti Direktur',
            'username' => 'siti@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'direktur',
        ]);

        User::create([
            'nama_user' => 'Eksekutif Monitoring',
            'username' => 'eksekutif@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'direktur',
        ]);
    }
}