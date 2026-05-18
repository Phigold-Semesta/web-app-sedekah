@extends('layouts.app')

@section('title', 'Master Kategori Barang | SEDEKAH')

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Master <span class="text-[#008f5d] dark:text-emerald-400">Kategori Barang</span>
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1 w-8 bg-[#008f5d] dark:bg-emerald-50 rounded-full"></span>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em]">
                    Sistem Otomasi Warta Administrasi Normatif LPSE Karawang
                </p>
            </div>
        </div>
        <a href="{{ route('admin.kategori_barang.create') }}" 
           class="inline-flex items-center px-8 py-4 bg-[#008f5d] dark:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-[2rem] hover:bg-emerald-700 dark:hover:bg-emerald-500 hover:shadow-[0_15px_30px_rgba(0,143,93,0.3)] transition-all duration-300 group shrink-0 shadow-xl">
            <i class="fas fa-plus-circle mr-2 group-hover:scale-110 transition-transform"></i>
            Tambah Kategori
        </a>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white dark:bg-slate-800 p-5 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-colors duration-300">
        <form action="{{ route('admin.kategori_barang.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            {{-- Search Input --}}
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                    <i class="fas fa-search text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama kategori barang..." 
                       class="w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 transition-all placeholder:text-slate-400">
            </div>

            <div class="flex flex-wrap gap-3">
                {{-- Filter Baris --}}
                <div class="relative">
                    <select name="per_page" onchange="this.form.submit()"
                            class="pl-6 pr-10 py-4 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 appearance-none cursor-pointer">
                        <option value="5" {{ request('per_page') == '5' ? 'selected' : '' }}>5 Baris</option>
                        <option value="10" {{ request('per_page') == '10' || !request('per_page') ? 'selected' : '' }}>10 Baris</option>
                        <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 Baris</option>
                        <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 Baris</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua Data</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                </div>

                <button type="submit" class="px-8 py-4 bg-emerald-100 dark:bg-emerald-900/30 text-[#008f5d] dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-[#008f5d] hover:text-white transition-all duration-300 shadow-sm active:scale-95">
                    <i class="fas fa-filter mr-2"></i> Apply Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="overflow-x-auto custom-scrollbar pb-4">
        <table class="w-full border-separate border-spacing-y-4">
            <thead>
                <tr class="text-[#008f5d] dark:text-emerald-400 text-[10px] font-black uppercase tracking-[0.3em] italic">
                    <th class="px-8 py-2 text-left" style="width: 120px;">ID Kategori</th>
                    <th class="px-8 py-2 text-left">Nama Kategori Barang</th>
                    <th class="px-8 py-2 text-center" style="width: 200px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategori as $item) 
                <tr class="bg-white dark:bg-slate-900 shadow-xl shadow-emerald-900/5 group transition-all hover:scale-[1.01]">
                    {{-- ID Kategori --}}
                    <td class="px-8 py-6 rounded-l-[2.5rem] border-y border-l border-emerald-50 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-black text-slate-400 uppercase tracking-widest italic">
                                #{{ str_pad($item->id_kategori_barang, 3, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    </td>

                    {{-- Nama Kategori --}}
                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-slate-800">
                        <div class="flex flex-col">
                            <span class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight group-hover:text-[#008f5d] transition-colors">
                                {{ $item->nama_kategori }}
                            </span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic mt-0.5">Klasifikasi Logistik</span>
                        </div>
                    </td>

                    {{-- Action Buttons --}}
                    <td class="px-8 py-6 rounded-r-[2.5rem] border-y border-r border-emerald-50 dark:border-slate-800 text-center">
                        <div class="flex justify-center gap-3">
                            {{-- Edit Button --}}
                            <a href="{{ route('admin.kategori_barang.edit', $item->id_kategori_barang) }}" 
                               title="Edit Kategori"
                               class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-400 hover:bg-amber-500 hover:text-white transition-all shadow-sm active:scale-90">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            
                            {{-- Delete Form & Button --}}
                            <form id="delete-form-{{ $item->id_kategori_barang }}" action="{{ route('admin.kategori_barang.destroy', $item->id_kategori_barang) }}" method="POST" class="inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="confirmDelete('{{ $item->id_kategori_barang }}', '{{ $item->nama_kategori }}')"
                                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-400 hover:bg-red-500 hover:text-white transition-all shadow-sm active:scale-90">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-8 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900/50 rounded-3xl flex items-center justify-center text-slate-200 dark:text-slate-700 mb-4 border border-slate-100 dark:border-slate-800">
                                <i class="fas fa-tags text-3xl"></i>
                            </div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Tidak ada kategori barang yang ditemukan dalam sistem</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Custom Pagination Section --}}
    @if($kategori instanceof \Illuminate\Pagination\LengthAwarePaginator && $kategori->hasPages())
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mt-6 bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-colors duration-300">
        <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest italic">
            Showing <span class="text-[#008f5d] dark:text-emerald-400">{{ $kategori->firstItem() }}</span> to <span class="text-[#008f5d] dark:text-emerald-400">{{ $kategori->lastItem() }}</span> of {{ $kategori->total() }} Entities
        </div>
        <div class="pagination-container">
            {{ $kategori->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
    @elseif(request('per_page') == 'all')
    <div class="mt-6 bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 text-center">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Menampilkan Semua Data ({{ $kategori->count() }} Kategori)</p>
    </div>
    @endif
</div>

<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #008f5d22; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #008f5d; }

    /* Gaya Pagination Kustom */
    .pagination-container nav > div:first-child { display: none; } 
    
    .pagination-container nav span[aria-current="page"] > span {
        background-color: #008f5d !important;
        border-color: #008f5d !important;
        color: white !important;
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0,143,93,0.2);
    }

    .pagination-container nav a, 
    .pagination-container nav span:not([aria-current="page"] > span) {
        border-radius: 0.75rem;
        margin: 0 3px;
        padding: 8px 14px !important;
        font-weight: 800;
        font-size: 10px;
        text-transform: uppercase;
        border: none !important;
        background-color: #f8fafc;
        color: #64748b;
        transition: all 0.3s ease;
    }

    .dark .pagination-container nav a, 
    .dark .pagination-container nav span:not([aria-current="page"] > span) {
        background-color: #0f172a;
        color: #94a3b8;
    }

    .pagination-container nav a:hover {
        background-color: #ecfdf5;
        color: #008f5d;
        transform: translateY(-2px);
    }

    .dark .pagination-container nav a:hover {
        background-color: #064e3b;
        color: #34d399;
    }
</style>

<script>
    /**
     * SweetAlert2 Notifikasi Otomatis untuk Create/Edit/Delete Kategori
     */
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                title: 'BERHASIL!',
                text: "{{ session('success') }}",
                icon: 'success',
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#fff' : '#1e293b',
                borderRadius: '2rem',
                confirmButtonColor: '#008f5d',
                customClass: {
                    title: 'font-black italic tracking-tighter',
                    confirmButton: 'rounded-xl font-black text-xs tracking-widest px-6 py-3 uppercase'
                }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'GAGAL!',
                text: "{{ session('error') }}",
                icon: 'error',
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#fff' : '#1e293b',
                borderRadius: '2rem',
                confirmButtonColor: '#ef4444',
                customClass: {
                    title: 'font-black italic tracking-tighter',
                    confirmButton: 'rounded-xl font-black text-xs tracking-widest px-6 py-3 uppercase'
                }
            });
        @endif
    });

    /**
     * Konfirmasi Hapus Kategori Eksklusif dengan SweetAlert2
     */
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'HAPUS KATEGORI?',
            html: `Anda akan menghapus kategori <b>${name}</b>.<br><span class="text-xs text-red-500 font-bold uppercase italic mt-2">Peringatan: Semua stok barang terkait mungkin akan terpengaruh!</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'YA, HAPUS!',
            cancelButtonText: 'BATAL',
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
            color: document.documentElement.classList.contains('dark') ? '#fff' : '#1e293b',
            borderRadius: '2rem',
            customClass: {
                title: 'font-black italic tracking-tighter',
                confirmButton: 'rounded-xl font-black text-xs tracking-widest px-6 py-3',
                cancelButton: 'rounded-xl font-black text-xs tracking-widest px-6 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const targetForm = document.getElementById('delete-form-' + id);
                if (targetForm) {
                    targetForm.submit();
                }
            }
        });
    }
</script>
@endsection