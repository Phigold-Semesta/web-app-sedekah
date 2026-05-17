@extends('layouts.app')

@section('title', 'Identity Profile | ' . $user->nama_user)

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate__animated animate__fadeInUp">
    {{-- Header Navigation --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Identity <span class="text-[#008f5d]">Profile</span>
            </h1>
            <p class="text-[10px] font-bold text-slate-500 dark:text-emerald-500/60 uppercase tracking-[0.3em] mt-1">
                SEDEKAH • System Authorization & Access Control 🔐
            </p>
        </div>
        {{-- PERBAIKAN RUTE: Bermigrasi ke rute admin yang baru --}}
        <a href="{{ route('admin.manajemen_user.index') }}" 
            class="flex items-center gap-3 px-6 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest hover:border-[#008f5d] hover:text-[#008f5d] transition-all shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali
        </a>
    </div>

    {{-- Detail Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-[3.5rem] border border-emerald-50 dark:border-emerald-900/20 shadow-2xl shadow-emerald-900/5 dark:shadow-none overflow-hidden relative">
        {{-- Decorative Background --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>

        <div class="p-8 md:p-14 relative z-10">
            
            {{-- Profile Section --}}
            <div class="flex flex-col md:flex-row items-center gap-10 mb-14 pb-14 border-b border-slate-100 dark:border-slate-800/50">
                <div class="relative">
                    <div class="w-36 h-36 bg-gradient-to-tr from-[#008f5d] to-emerald-400 rounded-[3rem] rotate-6 flex items-center justify-center shadow-2xl shadow-emerald-500/20 dark:shadow-none p-2">
                        <div class="w-full h-full bg-[#008f5d] rounded-[2.5rem] flex items-center justify-center text-white text-5xl font-black italic -rotate-6 border-4 border-white/10 uppercase">
                            {{ substr($user->nama_user, 0, 1) }}
                        </div>
                    </div>
                    {{-- Role Badge Floating --}}
                    <div class="absolute -bottom-2 -right-2 px-4 py-2 bg-slate-900 dark:bg-[#008f5d] text-white rounded-2xl text-[8px] font-black uppercase tracking-widest shadow-xl">
                        {{ $user->role }}
                    </div>
                </div>

                <div class="text-center md:text-left space-y-3">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-50 dark:bg-emerald-500/10 text-[#008f5d] dark:text-emerald-400 rounded-full text-[9px] font-black uppercase tracking-[0.2em]">
                        <span class="w-2 h-2 bg-[#008f5d] rounded-full animate-pulse"></span>
                        Account Verified
                    </div>
                    <h2 class="text-5xl font-black text-slate-900 dark:text-white tracking-tighter leading-none uppercase">{{ $user->nama_user }}</h2>
                    <p class="text-slate-400 dark:text-slate-500 font-bold text-xs uppercase tracking-[0.4em] italic">Access Protocol Level : {{ strtoupper($user->role) }}</p>
                </div>
            </div>

            {{-- Information Matrix --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Username --}}
                <div class="group space-y-3">
                    <label class="text-[10px] font-black text-slate-400 dark:text-emerald-600/50 uppercase tracking-[0.3em] ml-2">Internal Alias</label>
                    <div class="flex items-center gap-5 p-6 bg-slate-50/50 dark:bg-slate-800/40 rounded-[2rem] border border-transparent group-hover:border-emerald-100 dark:group-hover:border-emerald-900/30 transition-all">
                        <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center text-[#008f5d] shadow-sm text-lg">
                            <i class="fas fa-at"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Username</span>
                            <span class="text-lg font-black text-slate-700 dark:text-slate-200 tracking-tight">{{ $user->username }}</span>
                        </div>
                    </div>
                </div>

                {{-- Status Keamanan --}}
                <div class="group space-y-3">
                    <label class="text-[10px] font-black text-slate-400 dark:text-emerald-600/50 uppercase tracking-[0.3em] ml-2">Security Status</label>
                    <div class="flex items-center gap-5 p-6 bg-slate-50/50 dark:bg-slate-800/40 rounded-[2rem] border border-transparent group-hover:border-emerald-100 dark:group-hover:border-emerald-900/30 transition-all">
                        <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center text-emerald-500 shadow-sm text-lg">
                            <i class="fas fa-shield-check"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Authorization</span>
                            <span class="text-sm font-black text-emerald-600 dark:text-emerald-400 italic tracking-tighter">SECURED & ENCRYPTED</span>
                        </div>
                    </div>
                </div>

                {{-- User ID --}}
                <div class="group space-y-3">
                    <label class="text-[10px] font-black text-slate-400 dark:text-emerald-600/50 uppercase tracking-[0.3em] ml-2">Unique Identifier</label>
                    <div class="flex items-center gap-5 p-6 bg-slate-50/50 dark:bg-slate-800/40 rounded-[2rem] border border-transparent group-hover:border-emerald-100 dark:group-hover:border-emerald-900/30 transition-all">
                        <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center text-amber-500 shadow-sm text-lg">
                            <i class="fas fa-fingerprint"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">System ID</span>
                            <span class="text-lg font-black text-slate-700 dark:text-slate-200 tracking-[0.2em]">#{{ str_pad($user->id_user, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Registration Date --}}
                <div class="group space-y-3">
                    <label class="text-[10px] font-black text-slate-400 dark:text-emerald-600/50 uppercase tracking-[0.3em] ml-2">Onboarding Date</label>
                    <div class="flex items-center gap-5 p-6 bg-slate-50/50 dark:bg-slate-800/40 rounded-[2rem] border border-transparent group-hover:border-emerald-100 dark:group-hover:border-emerald-900/30 transition-all">
                        <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center text-[#008f5d] shadow-sm text-lg">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Registered</span>
                            <span class="text-sm font-black text-slate-700 dark:text-slate-200 uppercase tracking-tight">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="mt-14 pt-10 border-t border-slate-100 dark:border-slate-800 flex flex-col md:flex-row gap-5">
                @if(Auth::id() !== $user->id_user)
                {{-- PERBAIKAN RUTE & DIKSI: Mengubah dari destroy direktur ke admin, serta mengubah diksi tombol ke keamanan --}}
                <form id="delete-form-{{ $user->id_user }}" action="{{ route('admin.manajemen_user.destroy', $user->id_user) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete('{{ $user->id_user }}', '{{ $user->nama_user }}')" 
                        class="w-full bg-red-50 dark:bg-red-500/10 text-red-600 py-5 rounded-[2rem] text-[11px] font-black uppercase tracking-[0.2em] hover:bg-red-600 hover:text-white transition-all shadow-sm">
                        <i class="fas fa-user-slash mr-2"></i> Revoke Access
                    </button>
                </form>
                @else
                <div class="flex-1 bg-slate-100 dark:bg-slate-800/50 text-slate-400 py-5 rounded-[2rem] text-[10px] font-black uppercase tracking-widest text-center flex items-center justify-center gap-2 italic">
                    <i class="fas fa-lock text-xs"></i> Active Session Protocol
                </div>
                @endif

                {{-- PERBAIKAN RUTE: Edit dialihkan ke prefix admin --}}
                <a href="{{ route('admin.manajemen_user.edit', $user->id_user) }}" 
                    class="flex-[2] bg-[#008f5d] text-white py-5 rounded-[2rem] text-[11px] font-black uppercase tracking-[0.2em] text-center hover:bg-emerald-700 hover:shadow-2xl hover:shadow-emerald-900/20 transition-all active:scale-[0.98] shadow-lg flex items-center justify-center gap-3">
                    <i class="fas fa-user-gear"></i> Modify Account Details
                </a>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert Implementation --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, name) {
        const isDark = document.documentElement.classList.contains('dark');

        Swal.fire({
            title: 'REVOKE ACCESSIBILITY?',
            html: `You are about to revoke system access for <b class="text-[#008f5d]">${name}</b>.<br><p class="mt-3 text-[10px] text-red-500 font-black uppercase tracking-widest italic">This action will immediately terminate their authorization credentials!</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: isDark ? '#1e293b' : '#f1f5f9',
            confirmButtonText: 'REVOKE ACCESS',
            cancelButtonText: 'CANCEL',
            reverseButtons: true,
            background: isDark ? '#0f172a' : '#ffffff',
            color: isDark ? '#f1f5f9' : '#1e293b',
            customClass: {
                popup: `rounded-[3rem] border-4 ${isDark ? 'border-slate-800' : 'border-red-50'} shadow-2xl`,
                confirmButton: 'rounded-2xl font-black text-[10px] tracking-widest px-8 py-4 uppercase',
                cancelButton: `rounded-2xl font-black text-[10px] tracking-widest px-8 py-4 uppercase ${isDark ? 'text-slate-400' : 'text-slate-500'}`
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Revoking Credentials...',
                    html: '<p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Updating SEDEKAH database registry security matrix</p>',
                    background: isDark ? '#0f172a' : '#ffffff',
                    color: isDark ? '#f1f5f9' : '#1e293b',
                    didOpen: () => { Swal.showLoading() },
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-[2.5rem]' }
                });
                document.getElementById(`delete-form-${id}`).submit();
            }
        })
    }
</script>
@endsection