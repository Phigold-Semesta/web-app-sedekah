<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua user untuk dijadikan aktor log
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('Tidak ada user ditemukan. Pastikan UserSeeder sudah dijalankan!');
            return;
        }

        // Daftar aktivitas yang relevan dengan aplikasi SEDEKAH
        $activities = [
            'Melakukan login ke aplikasi SEDEKAH',
            'Menambahkan data muzakki baru ke sistem',
            'Memverifikasi penyaluran bantuan sosial',
            'Mengunduh laporan rekapitulasi dana sedekah (PDF)',
            'Mengubah detail informasi program donasi',
            'Menyetujui permohonan bantuan dari mustahik',
            'Melakukan pembaharuan status distribusi sembako',
            'Melihat dashboard statistik real-time aplikasi SEDEKAH',
            'Mengakses menu Manajemen Audit System',
            'Melakukan filter riwayat transaksi berdasarkan periode'
        ];

        $data = [];
        
        // Membuat 25 data log acak untuk mensimulasikan aktivitas
        for ($i = 0; $i < 25; $i++) {
            $user = $users->random();
            $data[] = [
                'id_user'    => $user->id_user,
                'aksi_log'   => $activities[array_rand($activities)],
                // Variasi waktu dalam 7 hari terakhir agar timeline terlihat hidup
                'waktu_log'  => Carbon::now()->subDays(rand(0, 7))->subHours(rand(1, 23))->subMinutes(rand(1, 59)),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Insert data sekaligus untuk efisiensi performa
        DB::table('audit_log')->insert($data);
        
        $this->command->info('25 data Audit Log aplikasi SEDEKAH berhasil disemai, bos!');
    }
}