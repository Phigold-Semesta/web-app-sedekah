<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SedekahMasterSeeder extends Seeder
{
    public function run(): void
    {
      

        // --- 2. DONATUR ---
        DB::table('donatur')->updateOrInsert(
            ['id_donatur' => 1],
            [
                'nama_donatur' => 'Hamba Allah',
                'no_hp'        => '08123456789',
                'alamat'       => 'Karawang Barat',
            ]
        );

        // --- 3. KUNJUNGAN ---
        DB::table('kunjungan')->updateOrInsert(
            ['id_kunjungan' => 1],
            [
                'id_donatur'      => 1,
                'tgl_kunjungan'   => Carbon::now(),
                'tujuan_kunjungan' => 'Donasi Sembako Bulanan',
            ]
        );

        // --- 4. DONASI (Induk Utama) ---
        DB::table('donasi')->updateOrInsert(
            ['id_donasi' => 1],
            [
                'id_kunjungan'  => 1,
                'id_user'       => 1,
                'tgl_donasi'    => Carbon::now(),
                'status_donasi' => 'Selesai',
                'bukti_donasi'  => 'default.jpg',
                'created_at'    => Carbon::now(),
            ]
        );

        // --- 5. KATEGORI BARANG ---
        DB::table('kategori_barang')->updateOrInsert(
            ['id_kategori_barang' => 1],
            ['nama_kategori' => 'Sembako']
        );

        // --- 6. DONASI_BARANG ---
        DB::table('donasi_barang')->insert([
            [
                'id_donasi'          => 1,
                'id_kategori_barang' => 1,
                'nama_barang'        => 'Beras Premium',
                'jumlah_barang'      => 10,
                'satuan_barang'      => 'Karung',
                'keterangan_sasaran' => 'Panti Asuhan A',
                'created_at'         => Carbon::now(),
            ]
        ]);

        // --- 7. PENILAIAN (Optional tapi ada di ERD) ---
        DB::table('penilaian')->updateOrInsert(
            ['id_penilaian' => 1],
            [
                'id_kunjungan' => 1,
                'skor_rating'  => 5,
                'saran'        => 'Pelayanan sangat baik dan transparan.',
            ]
        );
    }
}