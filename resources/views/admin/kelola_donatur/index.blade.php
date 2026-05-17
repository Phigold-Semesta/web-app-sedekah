@extends('layouts.app') {{-- Sesuaikan dengan nama file layout utama Anda --}}

@section('title', 'Kelola Data Donatur')
@section('page_title', 'Kelola Data Donatur')

@section('content')
<div class="space-y-8 animate-fade-in">
    <div class="bg-gradient-to-r from-[#065f46] to-[#008f5d] rounded-[2rem] p-8 text-white shadow-2xl relative overflow-hidden">
        <div class="absolute -right-10 -top-10 text-white/5 text-9xl font-black">
            <i class="fas fa-user-friends"></i>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <p class="text-[10px] font-black text-gold-accent uppercase tracking-[0.3em] mb-1">Manajemen Data</p>
                <h1 class="text-3xl font-extrabold tracking-tighter uppercase italic">Daftar Donatur Yayasan</h1>
                <p class="text-white/70 text-xs mt-2 max-w-xl">
                    Kelola, pantau, dan verifikasi profil seluruh donatur yang berkontribusi dalam sistem inventarisasi logistik dan keuangan sedekah.
                </p>
                <div class="mt-4">
                    <a href="{{ route('admin.donatur.create') }}" 
                       class="inline-flex items-center gap-2 px-5 py-3 bg-white text-[#008f5d] hover:bg-gold-accent hover:text-slate-900 rounded-xl text-xs font-black uppercase tracking-widest shadow-md transition-all duration-300 active:scale-95">
                        <i class="fas fa-plus-circle"></i> Tambah Donatur Manual
                    </a>
                </div>
            </div>
            
            <div class="w-full md:w-80">
                <form action="{{ route('admin.donatur.index') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama atau alamat..." 
                        class="w-full bg-white/10 backdrop-blur-md border border-white/20 text-white placeholder-white/50 text-xs font-bold rounded-2xl pl-12 pr-4 py-4 focus:outline-none focus:border-gold-accent transition-all shadow-inner">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-white/50 text-sm">
                        <i class="fas fa-search"></i>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'BERHASIL!',
                text: "{{ session('success') }}",
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b',
                confirmButtonColor: '#008f5d',
                customClass: { popup: 'rounded-[2.5rem]' }
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'GAGAL KESALAHAN!',
                text: "{{ session('error') }}",
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b',
                confirmButtonColor: '#ef4444',
                customClass: { popup: 'rounded-[2.5rem]' }
            });
        </script>
    @endif

    <div class="overflow-x-auto pb-4">
        <table class="w-full border-separate border-spacing-y-3 text-left">
            <thead>
                <tr class="text-[10px] font-black text-slate-400 dark:text-emerald-500/70 uppercase tracking-[0.25em] px-6">
                    <th class="pb-2 pl-6 w-20 text-center">Avatar</th>
                    <th class="pb-2">Informasi Profil Donatur</th>
                    <th class="pb-2 hidden md:table-cell">Kontak & Alamat</th>
                    <th class="pb-2 text-center">Aksi Manajemen</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donaturs as $donatur)
                    <tr class="bg-white dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800/50 rounded-3xl transition-all duration-300 hover:scale-[1.01] hover:shadow-xl group">
                        <td class="py-5 pl-6 rounded-l-[2rem] text-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($donatur->nama_donatur) }}&background=008f5d&color=fff&bold=true" 
                                class="w-12 h-12 rounded-2xl border-2 border-slate-100 dark:border-slate-700 shadow-md mx-auto group-hover:border-emerald-500 transition-colors">
                        </td>
                        
                        <td class="py-5">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-slate-400 dark:text-emerald-600 tracking-widest uppercase">ID: #{{ $donatur->id_donatur }}</span>
                                <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight mt-0.5 group-hover:text-emerald-500 transition-colors">
                                    {{ $donatur->nama_donatur }}
                                </h3>
                            </div>
                        </td>

                        <td class="py-5 hidden md:table-cell">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-300 flex items-center gap-2">
                                    <i class="fas fa-phone text-emerald-500 text-xs"></i> {{ $donatur->no_hp ?? '-' }}
                                </span>
                                <span class="text-[11px] font-medium text-slate-400 mt-1 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-slate-400 text-xs"></i> {{ $donatur->alamat ?? '-' }}
                                </span>
                            </div>
                        </td>

                        <td class="py-5 pr-6 rounded-r-[2rem] text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.donatur.show', $donatur->id_donatur) }}" 
                                   class="w-10 h-10 flex items-center justify-center bg-emerald-50 dark:bg-emerald-950/40 text-[#008f5d] hover:bg-[#008f5d] hover:text-white rounded-xl active:scale-95 transition-all shadow-sm"
                                   title="Lihat Profil Lengkap">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>

                                <a href="{{ route('admin.donatur.edit', $donatur->id_donatur) }}" 
                                   class="w-10 h-10 flex items-center justify-center bg-amber-50 dark:bg-amber-950/40 text-amber-600 hover:bg-amber-500 hover:text-white rounded-xl active:scale-95 transition-all shadow-sm"
                                   title="Edit Profil Donatur">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>

                                <button type="button" 
                                    onclick="deleteDonatur('{{ $donatur->id_donatur }}', '{{ $donatur->nama_donatur }}')"
                                    class="w-10 h-10 flex items-center justify-center bg-red-50 dark:bg-red-950/20 text-red-500 hover:bg-red-500 hover:text-white rounded-xl active:scale-95 transition-all shadow-sm"
                                    title="Hapus Donatur">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>

                                <form id="delete-form-{{ $donatur->id_donatur }}" action="{{ route('admin.donatur.destroy', $donatur->id_donatur) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-12 bg-white dark:bg-slate-900/20 rounded-[2rem]">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <i class="fas fa-user-slash text-4xl mb-3 text-slate-300"></i>
                                <p class="text-xs font-black uppercase tracking-widest">Tidak Ada Data Donatur Ditemukan</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $donaturs->links() }}
    </div>
</div>

<script>
    function deleteDonatur(id, name) {
        const isDark = document.documentElement.classList.contains('dark');
        Swal.fire({
            title: 'HAPUS DATA DONATUR?',
            text: `Akun donatur "${name.toUpperCase()}" akan dihapus permanen dari sistem.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: isDark ? '#334155' : '#f1f5f9',
            confirmButtonText: 'YA, HAPUS PERMANEN',
            cancelButtonText: 'BATAL',
            reverseButtons: true,
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f1f5f9' : '#1e293b',
            customClass: {
                popup: 'rounded-[2.5rem]',
                confirmButton: 'rounded-2xl font-black text-[10px] tracking-widest px-8 py-3',
                cancelButton: 'rounded-2xl font-black text-[10px] tracking-widest px-8 py-3 text-slate-500'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>
@endsection