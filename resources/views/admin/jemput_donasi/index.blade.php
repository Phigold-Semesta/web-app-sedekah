@extends('layouts.app')

@section('page_title', 'Jemput Donasi')

@section('content')
<!-- SweetAlert2 CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-5xl mx-auto px-4 py-8 animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Jemput <span class="text-[#008f5d] dark:text-emerald-400">Donasi</span>
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1 w-8 bg-[#008f5d] dark:bg-emerald-500 rounded-full"></span>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em]">
                    Daftar penjemputan barang yang siap diproses
                </p>
            </div>
        </div>
    </div>

    {{-- Filter Section (Sesuai Gambar) --}}
    <div class="bg-white dark:bg-slate-900 p-3 rounded-[2rem] border border-emerald-50 dark:border-slate-800 shadow-xl shadow-emerald-900/5 mb-8">
        <form action="{{ route('admin.jemput_donasi.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            {{-- Search Input --}}
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-emerald-600">
                    <i class="fas fa-search text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama barang..." 
                       class="w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-800 border-0 rounded-2xl text-[12px] font-bold focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 transition-all placeholder:text-slate-400">
            </div>

            {{-- Per Page Select --}}
            <select name="per_page" class="bg-slate-50 dark:bg-slate-800 border-0 rounded-2xl px-5 py-4 font-black text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-[#008f5d]/20 cursor-pointer w-full md:w-40">
                <option value="5" {{ request('per_page') == '5' ? 'selected' : '' }}>5 BARIS</option>
                <option value="10" {{ request('per_page') == '10' || !request('per_page') ? 'selected' : '' }}>10 BARIS</option>
                <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 BARIS</option>
                <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>SEMUA DATA</option>
            </select>

            {{-- Apply Button --}}
            <button type="submit" class="bg-[#dcfce7] dark:bg-emerald-900/40 text-[#008f5d] dark:text-emerald-400 px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-[#008f5d] hover:text-white transition-all active:scale-95 flex items-center justify-center gap-2">
                <i class="fas fa-filter"></i> APPLY FILTER
            </button>
        </form>
    </div>

    {{-- List Donasi Barang --}}
    <div class="space-y-4">
        @forelse($donasi_barang as $donasi)
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-emerald-50 dark:border-slate-800 shadow-xl shadow-emerald-900/5 transition-all duration-300 hover:scale-[1.01]">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-5 flex-1">
                        <div class="w-16 h-16 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600">
                            <i class="fas fa-box text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 dark:text-white text-lg uppercase tracking-tight">
                                {{ $donasi->nama_barang }}
                            </h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                Donatur: {{ $donasi->donasi->donatur->nama_donatur ?? 'N/A' }} | {{ $donasi->jumlah_barang }} {{ $donasi->satuan_barang }}
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('admin.jemput_donasi.update', $donasi->id_donasi) }}" method="POST" class="flex items-center gap-3">
                        @csrf
                        <input type="hidden" name="latitude" class="lat-field">
                        <input type="hidden" name="longitude" class="long-field">

                        <select name="status_pelacakan" class="status-select bg-slate-50 dark:bg-slate-800 border-0 rounded-2xl px-5 py-4 font-black text-[10px] uppercase tracking-widest text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-[#008f5d]/20 cursor-pointer">
                            <option value="Kurir Menuju Lokasi" {{ $donasi->status_donasi == 'Kurir Menuju Lokasi' ? 'selected' : '' }}>Kurir Menuju Lokasi</option>
                            <option value="Barang Dijemput" {{ $donasi->status_donasi == 'Barang Dijemput' ? 'selected' : '' }}>Barang Dijemput</option>
                            <option value="Tiba di Panti" {{ $donasi->status_donasi == 'Tiba di Panti' ? 'selected' : '' }}>Tiba di Panti</option>
                        </select>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.jemput_donasi.peta', $donasi->id_donasi) }}" 
                               title="Lihat Peta"
                               class="w-12 h-12 flex items-center justify-center rounded-2xl bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-600 hover:text-white transition-all shadow-sm active:scale-90">
                                <i class="fas fa-map-marked-alt"></i>
                            </a>
                            <button type="button" onclick="confirmUpdate(this.form)" title="Update Status"
                                    class="w-12 h-12 flex items-center justify-center rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 text-[#008f5d] dark:text-emerald-400 hover:bg-[#008f5d] hover:text-white transition-all shadow-sm active:scale-90">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <button type="button" onclick="confirmDelete('{{ $donasi->id_donasi }}', '{{ $donasi->nama_barang }}')" title="Hapus Data"
                                    class="w-12 h-12 flex items-center justify-center rounded-2xl bg-rose-50 dark:bg-rose-900/20 text-rose-500 hover:bg-rose-600 hover:text-white transition-all shadow-sm active:scale-90">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </form>
                    <form id="delete-form-{{ $donasi->id_donasi }}" action="{{ route('admin.jemput_donasi.destroy', $donasi->id_donasi) }}" method="POST" class="hidden">
                        @csrf @method('DELETE')
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-white dark:bg-slate-900 rounded-[2.5rem] border border-dashed border-slate-200 dark:border-slate-800">
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Tidak ada penjemputan barang saat ini.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination Sesuai Gambar --}}
    <div class="mt-8 bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-emerald-50 dark:border-slate-800 shadow-xl shadow-emerald-900/5 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
            JEMPUT LOG {{ $donasi_barang->firstItem() }}-{{ $donasi_barang->lastItem() }} OF {{ $donasi_barang->total() }} EVENTS
        </div>
        <div class="pagination-custom">
            {{ $donasi_barang->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
</div>

<style>
    /* Styling Pagination Sesuai Screenshot */
    .pagination-custom nav > div:first-child { display: none; }
    .pagination-custom nav span[aria-current="page"] > span {
        background-color: #008f5d !important;
        border-color: #008f5d !important;
        color: white !important;
        border-radius: 0.75rem;
    }
    .pagination-custom nav a, 
    .pagination-custom nav span:not([aria-current="page"] > span) {
        border-radius: 0.75rem !important;
        margin: 0 2px !important;
        padding: 8px 14px !important;
        font-weight: 800 !important;
        font-size: 10px !important;
        background-color: #f8fafc !important;
        color: #64748b !important;
        border: 1px solid #e2e8f0 !important;
    }
    .dark .pagination-custom nav a,
    .dark .pagination-custom nav span:not([aria-current="page"] > span) {
        background-color: #0f172a !important;
        border-color: #1e293b !important;
        color: #94a3b8 !important;
    }
</style>

<script>
    function confirmUpdate(form) {
        const status = form.querySelector('.status-select').value;
        Swal.fire({
            title: 'Konfirmasi Update', text: `Ubah status ke: ${status}?`,
            icon: 'question', showCancelButton: true, confirmButtonColor: '#008f5d', cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Update!', cancelButtonText: 'Batal',
            customClass: { confirmButton: 'rounded-xl font-black text-xs uppercase', cancelButton: 'rounded-xl font-black text-xs uppercase' }
        }).then((result) => {
            if (result.isConfirmed) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((pos) => {
                        form.querySelector('.lat-field').value = pos.coords.latitude;
                        form.querySelector('.long-field').value = pos.coords.longitude;
                        form.submit();
                    });
                } else { form.submit(); }
            }
        });
    }

    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Data?', text: `Anda akan menghapus data jemput ${name}.`,
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            customClass: { confirmButton: 'rounded-xl font-black text-xs uppercase', cancelButton: 'rounded-xl font-black text-xs uppercase' }
        }).then((result) => {
            if (result.isConfirmed) { document.getElementById('delete-form-' + id).submit(); }
        });
    }
</script>
@endsection