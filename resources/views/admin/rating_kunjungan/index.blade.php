@extends('layouts.app')

@section('title', 'Rating & Saran Layanan - Aplikasi SEDEKAH')

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                💝 Monitoring Rating <span class="text-[#046A38] dark:text-emerald-400">& Ulasan Sedekah</span>
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1 w-8 bg-[#046A38] dark:bg-emerald-500 rounded-full"></span>
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">
                    Pantau tingkat kepuasan, transparansi, serta saran dari pengguna aplikasi SEDEKAH secara real-time
                </p>
            </div>
        </div>
        <div class="shrink-0">
            <span class="inline-block bg-white dark:bg-slate-800 text-[#046A38] dark:text-emerald-400 font-black text-xs uppercase tracking-widest px-4 py-2.5 rounded-2xl shadow-sm border border-emerald-50 dark:border-slate-700">
                SEDEKAH Premium UI
            </span>
        </div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white dark:bg-slate-800 p-5 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-colors duration-300">
        <form action="{{ url()->current() }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            {{-- Search Input --}}
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#046A38] dark:group-focus-within:text-emerald-400 transition-colors">
                    <i class="fas fa-search text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama pengguna atau saran aplikasi..." 
                       class="w-full pl-11 pr-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#046A38]/20 dark:focus:ring-emerald-500/20 dark:text-slate-200 transition-all placeholder:text-slate-400 dark:placeholder:text-slate-600">
            </div>

            <div class="flex flex-wrap md:flex-nowrap gap-3">
                {{-- Skor Filter --}}
                <div class="w-full md:w-56 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-star text-xs"></i>
                    </div>
                    <select name="skor_rating" onchange="this.form.submit()"
                            class="w-full pl-10 pr-10 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#046A38]/20 dark:focus:ring-emerald-500/20 dark:text-slate-200 appearance-none cursor-pointer">
                        <option value="">--- Semua Rating Bintang ---</option>
                        <option value="5" {{ request('skor_rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 Bintang)</option>
                        <option value="4" {{ request('skor_rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (4 Bintang)</option>
                        <option value="3" {{ request('skor_rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ (3 Bintang)</option>
                        <option value="2" {{ request('skor_rating') == '2' ? 'selected' : '' }}>⭐⭐ (2 Bintang)</option>
                        <option value="1" {{ request('skor_rating') == '1' ? 'selected' : '' }}>⭐ (1 Bintang)</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit" class="px-6 py-3.5 bg-[#046A38] text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-[#03532B] transition-all duration-300 shadow-sm active:scale-95 flex items-center gap-2">
                        <i class="fas fa-filter text-xs"></i> Filter
                    </button>
                    
                    @if(request()->filled('search') || request()->filled('skor_rating'))
                        <a href="{{ url()->current() }}" class="px-4 py-3.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-2xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-all flex items-center justify-center" title="Reset Filter">
                            <i class="fas fa-undo text-xs"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Data Section dengan Desain Separated Rows --}}
    <div class="bg-transparent overflow-x-auto custom-scrollbar">
        <table class="w-full border-separate border-spacing-y-3.5 text-left min-w-[900px]">
            <thead>
                <tr class="text-nowrap px-8">
                    <th class="pl-8 pb-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] w-12 text-center">No</th>
                    <th class="pb-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Profil Pengguna</th>
                    <th class="pb-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Fitur / Program</th>
                    <th class="pb-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] w-36">Penilaian</th>
                    <th class="pb-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Waktu & Status</th>
                    <th class="pr-8 pb-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] text-center w-24">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rating_list as $rating)
                @php
                    $rowId = $rating->id_rating ?? $rating->id ?? $loop->index;
                    
                    // Memperbaiki ekstraksi nama dan email secara dinamis dan aman melalui relasi Eager Loading
                    $namaPengguna = $rating->user->name ?? ($rating->kunjungan->donatur->nama_donatur ?? ($rating->kunjungan->tamu->nama_tamu ?? 'Muzakki / Anonim'));
                    $emailPengguna = $rating->user->email ?? ($rating->kunjungan->donatur->no_hp ?? ($rating->kunjungan->gmail ?? ($rating->kunjungan->tamu->email ?? '-')));
                    $namaLayanan = $rating->program->nama_program ?? ($rating->kunjungan->layanan->nama_layanan ?? 'Transaksi Sedekah');
                @endphp

                <tr class="bg-white dark:bg-slate-800 hover:shadow-lg hover:shadow-emerald-950/5 dark:hover:shadow-black/30 border border-emerald-50/50 dark:border-slate-700/50 transition-all duration-200 group">
                    
                    {{-- Nomor urut --}}
                    <td class="pl-8 py-5 text-center font-bold text-xs text-slate-400 rounded-l-[1.8rem]">
                        {{ ($rating_list->currentPage() - 1) * $rating_list->perPage() + $loop->iteration }}
                    </td>

                    {{-- Profil Pengguna --}}
                    <td class="py-5">
                        <div class="flex items-center gap-4">
                            <div class="relative shrink-0">
                                <div class="p-0.5 rounded-[1.2rem] bg-gradient-to-tr from-emerald-100 to-white dark:from-emerald-900 dark:to-slate-800 shadow-sm group-hover:from-[#046A38] group-hover:to-emerald-400 transition-all duration-500">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($namaPengguna) }}&background=046A38&color=fff&bold=true" 
                                         class="w-11 h-11 rounded-[1.1rem] border-2 border-white dark:border-slate-700 object-cover group-hover:scale-105 transition-transform">
                                </div>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight truncate group-hover:text-[#046A38] dark:group-hover:text-emerald-400 transition-colors">
                                    {{ $namaPengguna }}
                                </p>
                                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider truncate mt-0.5">
                                    {{ str_contains($emailPengguna, '@') ? '📩' : '📞' }} {{ $emailPengguna }}
                                </p>
                            </div>
                        </div>
                    </td>

                    {{-- Fitur / Program --}}
                    <td class="py-5">
                        <span class="inline-block text-[10px] font-black text-[#046A38] dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1.5 rounded-xl border border-emerald-100/70 dark:border-emerald-800/30 text-nowrap">
                            📦 {{ $namaLayanan }}
                        </span>
                    </td>

                    {{-- Penilaian Star --}}
                    <td class="py-5">
                        <div class="flex items-center gap-0.5 text-amber-400">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $rating->skor_rating ? 'fas' : 'far' }} fa-star text-[11px]"></i>
                            @endfor
                            <span class="ml-1.5 text-[11px] font-black text-slate-700 dark:text-slate-300">({{ $rating->skor_rating }}/5)</span>
                        </div>
                    </td>

                    {{-- Waktu & Status Tanggapan --}}
                    <td class="py-5">
                        <div class="space-y-1.5">
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 text-nowrap">
                                <i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($rating->waktu_rating ?? $rating->created_at)->translatedFormat('d M Y, H:i') }} WIB
                            </p>
                            <div>
                                @if($rating->tanggapan)
                                    <span class="inline-flex items-center text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-xl border bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800/30">
                                        <i class="fas fa-check-circle mr-1"></i> Direspon
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-xl border bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/20 dark:text-amber-400 dark:border-amber-800/30">
                                        <i class="fas fa-clock mr-1 animate-pulse"></i> Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Aksi --}}
                    <td class="pr-8 py-5 text-nowrap text-center rounded-r-[1.8rem]">
                        <div class="flex justify-center items-center">
                            <button type="button" 
                                    onclick="openModalRating('{{ $rowId }}')"
                                    title="Lihat Ulasan & Beri Respon"
                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-500 hover:shadow-[0_4px_12px_rgba(37,99,235,0.2)] transition-all active:scale-90">
                                <i class="fas fa-comment-dots text-xs"></i>
                            </button>
                        </div>

                        {{-- MODAL INTERAKTIF DETAIL ULASAN & CRITIC --}}
                        <div id="modalDetailRating-{{ $rowId }}" class="fixed inset-0 z-50 hidden overflow-y-auto text-left" aria-hidden="true">
                            {{-- Backdrop --}}
                            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModalRating('{{ $rowId }}')"></div>
                            
                            {{-- Modal Content --}}
                            <div class="flex min-h-full items-center justify-center p-4 text-center">
                                <div class="relative w-full max-w-lg transform overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-800 text-left align-middle shadow-2xl transition-all border border-emerald-50 dark:border-slate-700 animate__animated animate__zoomIn animate__faster">
                                    {{-- Header --}}
                                    <div class="px-8 py-5 bg-gradient-to-r from-[#046A38] to-[#023e20] text-white flex items-center justify-between">
                                        <h3 class="text-xs font-black uppercase tracking-wider italic flex items-center gap-2">
                                            <i class="fas fa-heart text-amber-300"></i> Detail Kritik & Saran Sedekah
                                        </h3>
                                        <button type="button" onclick="closeModalRating('{{ $rowId }}')" class="text-white/70 hover:text-white transition-colors">
                                            <i class="fas fa-times text-md"></i>
                                        </button>
                                    </div>

                                    {{-- Body --}}
                                    <div class="p-8 space-y-6">
                                        <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border-l-4 border-[#FFF200]">
                                            <label class="block text-slate-400 dark:text-slate-500 text-[9px] font-black uppercase tracking-widest mb-1.5">Kritik & Saran Pengguna:</label>
                                            <p class="text-slate-700 dark:text-slate-300 text-xs font-bold italic leading-relaxed">
                                                "{{ $rating->saran ?? 'Pengguna tidak menuliskan ulasan teks (Hanya memberikan nilai bintang).' }}"
                                            </p>
                                        </div>

                                        {{-- Form Tanggapan Admin/Petugas Sedekah --}}
                                        <form action="{{ url('/admin/rating-kunjungan/' . ($rating->id_rating ?? $rating->id) . '/tanggapan') }}" method="POST" class="space-y-4">
                                            @csrf
                                            <div class="space-y-2">
                                                <label class="block text-slate-700 dark:text-slate-300 text-xs font-black uppercase tracking-tight">Kirim Respon / Evaluasi Sistem:</label>
                                                <textarea name="tanggapan" rows="3" required placeholder="Tulis tanggapan atau evaluasi untuk kenyamanan donasi..."
                                                          class="w-full p-4 bg-slate-50 dark:bg-slate-900 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#046A38]/20 dark:focus:ring-emerald-500/20 dark:text-slate-200 placeholder:text-slate-400 dark:placeholder:text-slate-600 leading-relaxed">{{ $rating->tanggapan ?? '' }}</textarea>
                                                <p class="text-[9px] text-slate-400 dark:text-slate-500 font-medium">*Respon ini ditujukan sebagai bentuk evaluasi internal atau feedback transparansi kepada pengguna.</p>
                                            </div>

                                            {{-- Actions Footer --}}
                                            <div class="flex justify-end gap-2 pt-2">
                                                <button type="button" onclick="closeModalRating('{{ $rowId }}')"
                                                        class="px-5 py-3 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-all">
                                                    Tutup
                                                </button>
                                                <button type="submit" 
                                                        class="px-5 py-3 bg-[#046A38] text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-[#03532B] shadow-lg shadow-emerald-900/20 transition-all flex items-center gap-2">
                                                    <i class="fas fa-paper-plane"></i> Simpan Respon
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="bg-white dark:bg-slate-800 rounded-[2.5rem] px-8 py-24 text-center border border-emerald-50 dark:border-slate-700 shadow-sm">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900/50 rounded-[2.2rem] flex items-center justify-center text-slate-200 dark:text-slate-700 mb-6 border border-slate-100 dark:border-slate-800">
                                <i class="fas fa-heart-broken text-3xl"></i>
                            </div>
                            <p class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Belum ada ulasan masuk</p>
                            <p class="text-[11px] text-slate-300 dark:text-slate-600 mt-2 italic font-bold">Data akan terisi otomatis setelah pengguna memberikan feedback aplikasi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Section --}}
    @if($rating_list instanceof \Illuminate\Pagination\LengthAwarePaginator && $rating_list->hasPages())
    <div class="px-10 py-6 bg-white dark:bg-slate-800 border border-emerald-50 dark:border-slate-700 rounded-[2.5rem] shadow-sm transition-colors duration-300">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-[#046A38] dark:bg-emerald-500"></div>
                <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest leading-none">
                    Tampil {{ $rating_list->firstItem() ?? 0 }} - {{ $rating_list->lastItem() ?? 0 }} dari {{ $rating_list->total() }} Total Ulasan
                </p>
            </div>
            <div class="custom-pagination">
                {{ $rating_list->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Script SweetAlert2 & Modal Handler --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openModalRating(id) {
        const modal = document.getElementById('modalDetailRating-' + id);
        if(modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModalRating(id) {
        const modal = document.getElementById('modalDetailRating-' + id);
        if(modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            iconColor: '#046A38',
            title: '<span class="text-2xl font-black italic uppercase tracking-tighter ' + (document.documentElement.classList.contains('dark') ? 'text-white' : 'text-slate-800') + '">Berhasil!</span>',
            text: "{{ session('success') }}",
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
            showConfirmButton: false,
            timer: 2500,
            customClass: {
                popup: 'rounded-[2.5rem] border-none shadow-2xl p-10',
                htmlContainer: 'text-xs font-bold text-slate-400 uppercase tracking-widest'
            }
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            iconColor: '#f43f5e',
            title: '<span class="text-2xl font-black italic uppercase tracking-tighter ' + (document.documentElement.classList.contains('dark') ? 'text-white' : 'text-slate-800') + '">Gagal!</span>',
            text: "{{ session('error') }}",
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
            showConfirmButton: true,
            confirmButtonText: 'TUTUP',
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-[2.5rem] border-none shadow-2xl p-10',
                confirmButton: 'px-8 py-3 bg-rose-600 text-white text-xs font-black uppercase tracking-widest rounded-full'
            }
        });
    @endif
</script>

<style>
    /* Custom Scrollbar Styling */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #046A3833; border-radius: 10px; transition: all 0.3s; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #046A38; }

    .dark .custom-scrollbar::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.5); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(16, 185, 129, 0.2); }

    /* Custom Tailwind Pagination Adjustments */
    .custom-pagination nav > div:first-child { display: none; }
    .custom-pagination nav span[aria-current="page"] > span {
        background: #046A38 !important;
        border-color: #046A38 !important;
        border-radius: 14px;
        font-weight: 800;
        font-size: 10px;
        color: white !important;
        box-shadow: 0 4px 12px rgba(4,106,56,0.2);
    }
    .custom-pagination nav a, .custom-pagination nav span {
        border-radius: 14px;
        margin: 0 3px;
        padding: 8px 14px !important;
        font-weight: 800;
        font-size: 10px;
        text-transform: uppercase;
        border: none !important;
        background-color: #ffffff;
        color: #64748b;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
    }

    .dark .custom-pagination nav a, .dark .custom-pagination nav span:not([aria-current="page"] > span) {
        background-color: #0f172a;
        color: #94a3b8;
    }
</style>
@endsection