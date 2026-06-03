@extends('layouts.donatur_app')

@section('page_title', 'Riwayat Donasi')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tighter">Riwayat Donasi</h2>
    <p class="text-slate-500 font-medium mt-1">Daftar jejak kebaikan yang telah Anda salurkan.</p>
</div>

<div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50">
                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jenis</th>
                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jumlah</th>
                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                <!-- Contoh baris data -->
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                    <td class="px-8 py-6 font-bold text-slate-600 dark:text-slate-300">03 Jun 2026</td>
                    <td class="px-8 py-6 font-bold text-slate-800 dark:text-white">Donasi Uang</td>
                    <td class="px-8 py-6 font-bold text-emerald-600">Rp 1.000.000</td>
                    <td class="px-8 py-6">
                        <span class="px-4 py-1 rounded-full text-[10px] font-black bg-yellow-100 text-yellow-700 uppercase">Pending</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Jika data kosong -->
    <div class="p-12 text-center">
        <p class="text-slate-400 font-medium">Belum ada riwayat donasi yang tercatat.</p>
    </div>
</div>
@endsection