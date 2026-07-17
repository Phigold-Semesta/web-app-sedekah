@extends('layouts.app')

@section('page_title', 'Peta Pelacakan Donasi')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    {{-- Back Button --}}
    <div class="mb-8">
        <a href="{{ route('admin.jemput_donasi.index') }}" 
           class="inline-flex items-center px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-2xl font-black uppercase tracking-widest text-[10px] transition-all duration-300 active:scale-95">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Jemput
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Peta Lokasi (Main Column) --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none">
                <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter mb-4">Peta Lokasi Kurir</h2>
                <div id="map" class="w-full h-[400px] rounded-3xl z-10 bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                    {{-- Map will be rendered here --}}
                </div>
            </div>
        </div>

        {{-- Timeline Riwayat (Side Column) --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none h-full">
                <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter mb-6">Riwayat Perjalanan</h2>
                
                <div class="relative border-l-2 border-slate-100 dark:border-slate-800 ml-3 space-y-8">
                    @forelse($donasi->donasi_barang->pelacakan as $track)
                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-emerald-500 border-4 border-white dark:border-slate-900"></div>
                            <h4 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight">{{ $track->status_pelacakan }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                {{ \Carbon\Carbon::parse($track->created_at)->format('d M Y, H:i') }}
                            </p>
                        </div>
                    @empty
                        <p class="text-slate-400 text-sm italic ml-4">Belum ada riwayat pergerakan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Leaflet CSS & JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil data pelacakan dari PHP ke JS
        const tracks = @json($donasi->donasi_barang->pelacakan);
        
        if (tracks.length > 0) {
            // Inisialisasi Peta (pusat pada koordinat terakhir)
            const latest = tracks[0];
            const map = L.map('map').setView([latest.latitude, latest.longitude], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Tambahkan Marker untuk setiap titik
            tracks.forEach((track, index) => {
                L.marker([track.latitude, track.longitude])
                    .addTo(map)
                    .bindPopup(`
                        <div class="font-bold text-xs">
                            <span class="block uppercase">${track.status_pelacakan}</span>
                            <span class="text-slate-400">${track.created_at}</span>
                        </div>
                    `);
            });
        } else {
            // Jika belum ada data
            document.getElementById('map').innerHTML = '<div class="text-slate-400 font-bold uppercase tracking-widest text-center">Data Peta Belum Tersedia</div>';
        }
    });
</script>
@endsection