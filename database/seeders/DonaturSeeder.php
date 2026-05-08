<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donatur;

class DonaturSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $donaturBaru = [
            [
                'nama_donatur' => 'Haji Muhammad Yusuf',
                'no_hp'        => '081234567890',
                'alamat'       => 'Jl. Raya Galuh Mas No. 12, Karawang',
            ],
            [
                'nama_donatur' => 'Siti Aminah Kusuma',
                'no_hp'        => '085712345678',
                'alamat'       => 'Perumahan Resinda Blok B3, Karawang Barat',
            ],
            [
                'nama_donatur' => 'Bambang Sudjatmiko',
                'no_hp'        => '081388990011',
                'alamat'       => 'Jl. Ahmad Yani Gg. Musholla, Karawang Timur',
            ],
            [
                'nama_donatur' => 'Dewi Sartika Putri',
                'no_hp'        => '089566778899',
                'alamat'       => 'Dusun Krajan RT 04/02, Klari, Karawang',
            ],
            [
                'nama_donatur' => 'Agus Setiawan',
                'no_hp'        => '081211223344',
                'alamat'       => 'Jl. Interchange Tol Karawang Barat No. 5',
            ],
            [
                'nama_donatur' => 'Hj. Ratna Sari Dewi',
                'no_hp'        => '087855443322',
                'alamat'       => 'Kp. Telukjambe No. 88, Karawang Pusat',
            ],
            [
                'nama_donatur' => 'Andi Wijaya',
                'no_hp'        => '085299008877',
                'alamat'       => 'Perum Grand Taruma Blok C, Karawang',
            ],
            [
                'nama_donatur' => 'Lestari Handayani',
                'no_hp'        => '081344556677',
                'alamat'       => 'Jl. Kertabumi No. 10, Karawang',
            ],
            [
                'nama_donatur' => 'Faisal Basri',
                'no_hp'        => '082166778811',
                'alamat'       => 'Kawasan Industri KIIC Gg. Swadaya, Karawang',
            ],
            [
                'nama_donatur' => 'Sri Wahyuni',
                'no_hp'        => '081288776655',
                'alamat'       => 'Jl. Tuparev Gg. Kenanga, Karawang',
            ],
        ];

        foreach ($donaturBaru as $data) {
            Donatur::create([
                'nama_donatur' => $data['nama_donatur'],
                'no_hp'        => $data['no_hp'],
                'alamat'       => $data['alamat'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}