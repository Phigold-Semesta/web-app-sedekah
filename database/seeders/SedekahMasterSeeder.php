<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SedekahMasterSeeder extends Seeder
{
    public function run(): void
    {
        

        // --- 2. DATA DONATUR ---
        DB::table('donatur')->updateOrInsert(
            ['id_donatur' => 1],
            [
                'nama_donatur' => 'pak yanto',
                'no_hp'        => '08123456789',
                'alamat'       => 'Karawang Barat',
            ]
        );

        // --- 3. LOOPING UNTUK MENGHASILKAN 6 RECORD DATA RELASIONAL ---
        $donasiUangData = [
            ['nominal' => 500000,  'order' => 'TRX-20260504-001'],
            ['nominal' => 1250000, 'order' => 'TRX-20260504-002'],
            ['nominal' => 75000,   'order' => 'TRX-20260504-003'],
            ['nominal' => 2500000, 'order' => 'TRX-20260504-004'],
            ['nominal' => 300000,  'order' => 'TRX-20260504-005'],
            ['nominal' => 150000,  'order' => 'TRX-20260504-006'],
        ];

        foreach ($donasiUangData as $index => $item) {
            $id = $index + 1;
            // Membuat variasi tanggal agar laporan terlihat dinamis
            $tanggal = Carbon::now()->subDays(6 - $id);

            // A. Create Kunjungan
            DB::table('kunjungan')->updateOrInsert(
                ['id_kunjungan' => $id],
                [
                    'id_donatur'       => 1,
                    'tgl_kunjungan'    => $tanggal,
                    'tujuan_kunjungan' => 'Donasi Rutin Ke-' . $id,
                ]
            );

            // B. Create Donasi (Induk)
            DB::table('donasi')->updateOrInsert(
                ['id_donasi' => $id],
                [
                    'id_kunjungan'  => $id,
                    'id_user'       => 1,
                    'tgl_donasi'    => $tanggal,
                    'status_donasi' => 'Selesai',
                    'bukti_donasi'  => 'default.jpg',
                    'created_at'    => $tanggal,
                ]
            );

            // C. Create Donasi_Uang (Detail)
            DB::table('donasi_uang')->updateOrInsert(
                ['id_donasi_uang' => $id],
                [
                    'id_donasi'  => $id,
                    'order_id'   => $item['order'],
                    'nominal'    => $item['nominal'],
                    'snap_token' => 'snap_token_dummy_' . $id,
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]
            );

            // D. Create Penilaian (Optional per Kunjungan)
            DB::table('penilaian')->updateOrInsert(
                ['id_penilaian' => $id],
                [
                    'id_kunjungan' => $id,
                    'skor_rating'  => 5,
                    'saran'        => 'Terima kasih, proses donasi ke-' . $id . ' sangat mudah.',
                ]
            );
        }

        // --- 4. DATA TAMBAHAN (KATEGORI & BARANG) ---
        DB::table('kategori_barang')->updateOrInsert(
            ['id_kategori_barang' => 1],
            ['nama_kategori' => 'Sembako']
        );

        DB::table('donasi_barang')->updateOrInsert(
            ['id_donasi_barang' => 1],
            [
                'id_donasi'          => 1, // Menempel pada donasi pertama
                'id_kategori_barang' => 1,
                'nama_barang'        => 'Beras Premium',
                'jumlah_barang'      => 10,
                'satuan_barang'      => 'Karung',
                'keterangan_sasaran' => 'Panti Asuhan A',
                'created_at'         => Carbon::now(),
            ]
        );
    }
}