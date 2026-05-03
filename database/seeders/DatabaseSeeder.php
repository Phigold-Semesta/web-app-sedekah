<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        

        // Panggil Master Seeder Project SEDEKAH di sini
        $this->call([
            SedekahMasterSeeder::class,
            // Jika nanti ada seeder lain (misal: AuditLogSeeder), tinggal tambah di sini
        ]);

    }
}
