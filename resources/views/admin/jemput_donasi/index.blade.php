@extends('layouts.app')

@section('page_title', 'Jemput Donasi')

@section('content')
<!-- SweetAlert2 CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-5xl mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="mb-10 text-left">
        <h1 class="text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tighter">Jemput Donasi</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium mt-2">Daftar penjemputan barang yang siap diproses.</p>
    </div>

    {{-- Daftar Donasi --}}
    <div class="space-y-4">
        @forelse($donasi_barang as $donasi)
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    {{-- Info Barang --}}
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600">
                            <i class="fas fa-box text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 dark:text-white text-lg uppercase">{{ $donasi->nama_barang }}</h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                                Donatur: {{ $donasi->donasi->donatur->nama_donatur ?? 'N/A' }} | {{ $donasi->jumlah_barang }} {{ $donasi->satuan_barang }}
                            </p>
                        </div>
                    </div>

                    {{-- Form Update Status --}}
                    <form action="{{ route('admin.jemput_donasi.update', $donasi->id_donasi) }}" method="POST" class="flex flex-wrap items-center gap-3">
                        @csrf
                        <input type="hidden" name="latitude" class="lat-field">
                        <input type="hidden" name="longitude" class="long-field">

                        <select name="status_pelacakan" class="status-select bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 font-bold text-slate-700 dark:text-slate-300 text-sm focus:ring-2 focus:ring-emerald-500">
                            <option value="Kurir Menuju Lokasi" {{ $donasi->status_donasi == 'Kurir Menuju Lokasi' ? 'selected' : '' }}>Kurir Menuju Lokasi</option>
                            <option value="Barang Dijemput" {{ $donasi->status_donasi == 'Barang Dijemput' ? 'selected' : '' }}>Barang Dijemput</option>
                            <option value="Tiba di Panti" {{ $donasi->status_donasi == 'Tiba di Panti' ? 'selected' : '' }}>Tiba di Panti</option>
                        </select>

                        <div class="flex gap-2">
                            {{-- Tombol Lihat Peta (Baru) --}}
                            <a href="{{ route('admin.jemput_donasi.peta', $donasi->id_donasi) }}" 
                               class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-3 rounded-2xl font-black uppercase text-[10px] tracking-widest transition-all active:scale-95 flex items-center gap-2">
                                <i class="fas fa-map-marked-alt"></i> Peta
                            </a>

                            {{-- Tombol Update --}}
                            <button type="button" onclick="confirmUpdate(this.form)" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg transition-all active:scale-95">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-20">
                <p class="text-slate-400 font-bold uppercase tracking-widest">Tidak ada penjemputan barang saat ini.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    function confirmUpdate(form) {
        const status = form.querySelector('.status-select').value;
        
        Swal.fire({
            title: 'Konfirmasi Update',
            text: `Ubah status ke: ${status}? Lokasi Anda akan tercatat.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Update!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((position) => {
                        form.querySelector('.lat-field').value = position.coords.latitude;
                        form.querySelector('.long-field').value = position.coords.longitude;
                        form.submit();
                    }, (error) => {
                        Swal.fire('Gagal!', 'GPS tidak aktif. Mohon aktifkan GPS.', 'error');
                    });
                } else {
                    Swal.fire('Error', 'Browser tidak mendukung GPS.', 'error');
                }
            }
        });
    }
</script>
@endsection