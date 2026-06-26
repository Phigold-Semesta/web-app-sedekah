@extends('layouts.donatur_app')

@section('page_title', 'Riwayat Donasi')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tighter">Riwayat Donasi</h2>
        <p class="text-slate-500 font-medium mt-1">Daftar jejak kebaikan yang telah Anda salurkan.</p>
    </div>
    
    <div class="flex items-center gap-3 bg-white dark:bg-slate-900 p-2 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-2">Tampil:</span>
        <select onchange="window.location.href = this.value" class="bg-slate-50 dark:bg-slate-800 border-0 rounded-lg text-xs font-bold text-slate-700 dark:text-white p-2 cursor-pointer focus:ring-0 focus:outline-none">
            <option value="{{ request()->fullUrlWithQuery(['per_page' => 5]) }}" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
            <option value="{{ request()->fullUrlWithQuery(['per_page' => 10]) }}" {{ request('per_page') == 10 || !request('per_page') ? 'selected' : '' }}>10</option>
            <option value="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
            <option value="{{ request()->fullUrlWithQuery(['per_page' => 99999]) }}" {{ request('per_page') == 99999 ? 'selected' : '' }}>Semua</option>
        </select>
    </div>
</div>

<div class="bg-white dark:bg-slate-900 rounded-3xl shadow-lg border border-slate-100 dark:border-slate-800 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50">
                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jenis</th>
                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Detail Donasi</th>
                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($riwayatDonasi as $item)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-all duration-200">
                    <td class="px-8 py-6 font-bold text-slate-600 dark:text-slate-300">
                        {{ \Carbon\Carbon::parse($item->tgl_donasi)->format('d M Y') }}
                    </td>
                    <td class="px-8 py-6 font-bold text-slate-800 dark:text-white uppercase tracking-tight">
                        Donasi {{ $item->jenis_donasi }}
                    </td>
                    <td class="px-8 py-6 font-bold text-emerald-600">
                        @if(trim(strtolower($item->jenis_donasi)) == 'uang')
                            Rp {{ number_format(optional($item->donasiUang)->nominal ?? ($item->jumlah ?? 0), 0, ',', '.') }}
                        @else
                            <div class="text-slate-800 dark:text-white">{{ $item->nama_barang ?? '-' }}</div>
                            <div class="text-[11px] text-slate-400 font-medium">{{ $item->jumlah_barang ?? 0 }} {{ $item->satuan ?? '' }}</div>
                        @endif
                    </td>
                    <td class="px-8 py-6">
                        @php
                            $rawStatus = trim(strtolower($item->status_donasi));
                            // Map Status
                            $statusMap = [
                                'berhasil' => 'donasi berhasil terkirim',
                                'settlement' => 'donasi berhasil terkirim',
                                'belum bayar' => 'pending',
                            ];
                            $status = $statusMap[$rawStatus] ?? $rawStatus;
                            
                            // Map Warna
                            $colors = [
                                'pending' => 'bg-orange-100 text-orange-700', // Warna Orange diminta
                                'donasi berhasil terkirim' => 'bg-emerald-100 text-emerald-700',
                                'donasi gagal' => 'bg-red-100 text-red-700',
                            ];
                            $colorClass = $colors[$status] ?? 'bg-slate-100 text-slate-600';
                        @endphp
                        <span class="px-4 py-1.5 rounded-full text-[10px] font-black {{ $colorClass }} uppercase tracking-wider">
                            {{ $status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-12 text-center text-slate-400 font-medium italic">
                        Belum ada riwayat donasi yang tercatat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-8 py-6 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50">
            {{ $riwayatDonasi->appends(request()->input())->links() }}
        </div>
    </div>
</div>
@endsection