@extends('layouts.app')

@section('title', 'User Management | SEDEKAH')

@section('page_title', 'Manajemen User')

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Manajemen <span class="text-[#008f5d] dark:text-emerald-400">User</span>
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1 w-8 bg-[#008f5d] dark:bg-emerald-500 rounded-full"></span>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em]">
                    Sistem Manajemen Otoritas Yayasan Rumah Harapan
                </p>
            </div>
        </div>
        <a href="{{ route('direktur.manajemen_user.create') }}" 
           class="inline-flex items-center px-8 py-4 bg-[#008f5d] dark:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-[2rem] hover:bg-emerald-700 dark:hover:bg-emerald-500 hover:shadow-[0_15px_30px_rgba(0,143,93,0.3)] transition-all duration-300 group shrink-0 shadow-xl">
            <i class="fas fa-user-plus mr-2 group-hover:scale-110 transition-transform"></i>
            Add New User
        </a>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white dark:bg-slate-800 p-5 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-colors duration-300">
        <form action="{{ route('direktur.manajemen_user.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            {{-- Search Input --}}
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                    <i class="fas fa-search text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari identitas user..." 
                       class="w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 transition-all placeholder:text-slate-400">
            </div>

            <div class="flex flex-wrap gap-3">
                {{-- Filter Baris (Per Page) --}}
                <select name="per_page" onchange="this.form.submit()"
                        class="pl-6 pr-10 py-4 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 appearance-none cursor-pointer">
                    <option value="5" {{ request('per_page') == '5' ? 'selected' : '' }}>5 Baris</option>
                    <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 Baris</option>
                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua Data</option>
                </select>

                {{-- Filter Role --}}
                <select name="role" onchange="this.form.submit()"
                        class="pl-6 pr-10 py-4 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 appearance-none cursor-pointer">
                    <option value="">All Roles</option>
                    <option value="administrator" {{ request('role') == 'administrator' ? 'selected' : '' }}>Administrator</option>
                    <option value="direktur" {{ request('role') == 'direktur' ? 'selected' : '' }}>Direktur</option>
                    <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                </select>

                <button type="submit" class="px-8 py-4 bg-emerald-100 dark:bg-emerald-900/30 text-[#008f5d] dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-[#008f5d] hover:text-white transition-all duration-300 shadow-sm active:scale-95">
                    <i class="fas fa-filter mr-2"></i> Apply
                </button>
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="overflow-x-auto custom-scrollbar pb-4">
        <table class="w-full border-separate border-spacing-y-4">
            <thead>
                <tr class="text-[#008f5d] dark:text-emerald-400 text-[10px] font-black uppercase tracking-[0.3em] italic">
                    <th class="px-8 py-2 text-left">Identity</th>
                    <th class="px-8 py-2 text-left">Credential</th>
                    <th class="px-8 py-2 text-left">Role Access</th>
                    <th class="px-8 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($user_list as $user) 
                <tr class="bg-white dark:bg-slate-900 shadow-xl shadow-emerald-900/5 group transition-all hover:scale-[1.01]">
                    {{-- Avatar & Nama --}}
                    <td class="px-8 py-6 rounded-l-[2.5rem] border-y border-l border-emerald-50 dark:border-slate-800">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-tr from-emerald-100 to-white dark:from-emerald-900 dark:to-slate-800 rounded-2xl flex items-center justify-center text-emerald-600 font-black italic uppercase shadow-sm border border-emerald-50 dark:border-slate-700">
                                {{ substr($user->nama_user, 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight group-hover:text-[#008f5d] transition-colors">
                                    {{ $user->nama_user }}
                                </span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">User ID #{{ $user->id_user }}</span>
                            </div>
                        </div>
                    </td>

                    {{-- Username --}}
                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-slate-800">
                        <div class="flex flex-col">
                            <span class="text-[11px] font-black text-[#008f5d] dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1.5 rounded-xl border border-emerald-100 dark:border-emerald-800/30 w-fit">
                                @ {{ $user->username }}
                            </span>
                        </div>
                    </td>

                    {{-- Role Badge --}}
                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-slate-800">
                        @php
                            $roleClasses = [
                                'administrator' => 'bg-red-50 text-red-600 border-red-100 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800/30',
                                'petugas' => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800/30',
                                'direktur' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/20 dark:text-amber-400 dark:border-amber-800/30',
                            ];
                            $class = $roleClasses[strtolower($user->role)] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                        @endphp
                        <span class="inline-flex items-center text-[9px] font-black uppercase tracking-[0.15em] px-4 py-2 rounded-xl border {{ $class }} italic shadow-sm">
                            <i class="fas fa-shield-halved mr-1.5 opacity-70"></i>
                            {{ $user->role }}
                        </span>
                    </td>

                    {{-- Action Buttons --}}
                    <td class="px-8 py-6 rounded-r-[2.5rem] border-y border-r border-emerald-50 dark:border-slate-800 text-center">
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('direktur.manajemen_user.show', $user->id_user) }}" 
                               class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-400 hover:bg-[#008f5d] hover:text-white transition-all shadow-sm active:scale-90">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('direktur.manajemen_user.edit', $user->id_user) }}" 
                               class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-400 hover:bg-amber-500 hover:text-white transition-all shadow-sm active:scale-90">
                                <i class="fas fa-user-edit text-xs"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900/50 rounded-3xl flex items-center justify-center text-slate-200 dark:text-slate-700 mb-4 border border-slate-100 dark:border-slate-800">
                                <i class="fas fa-users-slash text-3xl"></i>
                            </div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">No users found in the galaxy</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Custom Pagination Section --}}
    @if(method_exists($user_list, 'hasPages') && $user_list->hasPages())
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mt-6 bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-colors duration-300">
        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
            Showing <span class="text-[#008f5d]">{{ $user_list->firstItem() }}</span> to <span class="text-[#008f5d]">{{ $user_list->lastItem() }}</span> of {{ $user_list->total() }} Users
        </div>
        <div class="pagination-container">
            {{ $user_list->links('pagination::tailwind') }}
        </div>
    </div>
    @endif
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #008f5d22; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #008f5d; }

    /* Styling Tailwind Pagination agar selaras dengan tema Emerald */
    .pagination-container nav span[aria-current="page"] > span {
        background-color: #008f5d !important;
        border-color: #008f5d !important;
        border-radius: 0.75rem;
    }
    .pagination-container nav a, .pagination-container nav span {
        border-radius: 0.75rem;
        margin: 0 2px;
        font-weight: 800;
        font-size: 10px;
        text-transform: uppercase;
    }
</style>
@endsection