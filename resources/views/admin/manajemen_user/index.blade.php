@extends('layouts.app')

@section('title', 'Security Registry | Management')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 animate__animated animate__fadeIn">
    {{-- Header Dashboard --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                User <span class="text-[#008f5d]">Registry</span>
            </h1>
            <p class="text-[10px] font-bold text-slate-500 dark:text-emerald-500/60 uppercase tracking-[0.3em] mt-1">
                SEDEKAH • Authorization & Access Infrastructure 🔑
            </p>
        </div>
        <a href="{{ route('direktur.manajemen_user.create') }}" 
            class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-[#008f5d] text-white rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] shadow-lg shadow-emerald-900/20 hover:bg-emerald-700 hover:scale-[1.02] active:scale-[0.98] transition-all self-start md:self-auto">
            <i class="fas fa-plus-circle"></i> Provision New Identity
        </a>
    </div>

    {{-- Info Card / Filter Utility --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 border border-emerald-50 dark:border-emerald-900/20 shadow-xl shadow-emerald-900/5 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4 w-full md:w-auto">
            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-500/10 rounded-2xl flex items-center justify-center text-[#008f5d]">
                <i class="fas fa-users-shield text-lg"></i>
            </div>
            <div>
                <h3 class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider">Total Core Operators</h3>
                <p class="text-2xl font-black text-[#008f5d] mt-0.5">{{ $users->total() }} <span class="text-xs font-bold text-slate-400 uppercase tracking-normal">Identities</span></p>
            </div>
        </div>
        
        {{-- Custom Search Bar --}}
        <form action="{{ route('direktur.manajemen_user.index') }}" method="GET" class="w-full md:w-80 relative group">
            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                <i class="fas fa-search text-xs"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}"
                class="w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent rounded-[1.5rem] text-xs font-bold text-slate-700 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-[#008f5d] focus:bg-white dark:focus:bg-slate-900 transition-all uppercase placeholder:normal-case"
                placeholder="Search operational alias...">
        </form>
    </div>

    {{-- Luxurious Table Container --}}
    <div class="overflow-x-auto pb-4">
        <table class="w-full border-separate border-spacing-y-4 text-left">
            <thead>
                <tr class="text-[10px] font-black text-slate-400 dark:text-emerald-600/50 uppercase tracking-[0.3em] px-6">
                    <th class="pb-2 pl-8">Operator</th>
                    <th class="pb-2">Protocol Identifier</th>
                    <th class="pb-2">Privilege Level</th>
                    <th class="pb-2">Onboarding</th>
                    <th class="pb-2 pr-8 text-center">Authorization Control</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr class="bg-white dark:bg-slate-900 border border-emerald-50 dark:border-emerald-900/10 shadow-sm rounded-[2rem] hover:scale-[1.005] hover:shadow-md transition-all group">
                    {{-- Avatar & Nama --}}
                    <td class="py-6 pl-8 rounded-l-[2rem]">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-tr from-[#008f5d] to-emerald-400 rounded-2xl flex items-center justify-center text-white font-black italic uppercase shadow-md shadow-emerald-500/10">
                                {{ substr($u->nama_user, 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight">{{ $u->nama_user }}</span>
                                <span class="text-[10px] font-bold text-slate-400 tracking-wider">SECURE ACCOUNT</span>
                            </div>
                        </div>
                    </td>
                    
                    {{-- Username --}}
                    <td class="py-6">
                        <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300 font-bold text-xs">
                            <span class="text-[#008f5d]">@</span>{{ $u->username }}
                        </div>
                    </td>
                    
                    {{-- Role Badge --}}
                    <td class="py-6">
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest italic {{ $u->role === 'direktur' ? 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400' : 'bg-emerald-50 dark:bg-emerald-500/10 text-[#008f5d] dark:text-emerald-400' }}">
                            {{ $u->role }}
                        </span>
                    </td>
                    
                    {{-- Created At --}}
                    <td class="py-6 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-tight">
                        {{ $u->created_at->format('d M Y') }}
                    </td>
                    
                    {{-- Action Control Buttons --}}
                    <td class="py-6 pr-8 rounded-r-[2rem] text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('direktur.manajemen_user.show', $u->id_user) }}" 
                                class="p-3 bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-[#008f5d] rounded-xl transition-colors" title="View Profile">
                                <i class="fas fa-shield-halved text-xs"></i>
                            </a>
                            <a href="{{ route('direktur.manajemen_user.edit', $u->id_user) }}" 
                                class="p-3 bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-amber-500 rounded-xl transition-colors" title="Modify Details">
                                <i class="fas fa-user-gear text-xs"></i>
                            </a>
                            @if(Auth::id() !== $u->id_user)
                            <form id="delete-form-{{ $u->id_user }}" action="{{ route('direktur.manajemen_user.destroy', $u->id_user) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('{{ $u->id_user }}', '{{ $u->nama_user }}')" 
                                    class="p-3 bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-red-500 rounded-xl transition-colors" title="Archive Identity">
                                    <i class="fas fa-archive text-xs"></i>
                                </button>
                            </form>
                            @else
                            <button class="p-3 bg-slate-100 dark:bg-slate-800/30 text-slate-300 dark:text-slate-700 rounded-xl cursor-not-allowed" title="Active Core Session" disabled>
                                <i class="fas fa-lock text-xs"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 bg-white dark:bg-slate-900 rounded-[2rem] text-center border border-dashed border-slate-200 dark:border-slate-800">
                        <div class="flex flex-col items-center justify-center gap-3">
                            <i class="fas fa-folder-open text-slate-300 text-3xl"></i>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest italic">No system identities registered under query.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Layout --}}
    <div class="pt-2">
        {{ $users->links() }}
    </div>
</div>

{{-- SweetAlert Integration --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, name) {
        const isDark = document.documentElement.classList.contains('dark');

        Swal.fire({
            title: 'ARCHIVE IDENTITY?',
            html: `You are about to archive the user <b class="text-[#008f5d]">${name}</b>.<br><p class="mt-3 text-[10px] text-red-500 font-black uppercase tracking-widest italic">This action will restrict system access immediately!</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: isDark ? '#1e293b' : '#f1f5f9',
            confirmButtonText: 'CONFIRM ARCHIVE',
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
                    title: 'Synchronizing...',
                    html: '<p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Updating SEDEKAH database registry</p>',
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