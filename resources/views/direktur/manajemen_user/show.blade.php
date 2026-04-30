@extends('layouts.app')

@section('title', 'Identity Profile | SEDEKAH')

@section('content')
<div class="p-4 md:p-10 bg-slate-50 dark:bg-slate-950 min-h-screen">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white dark:bg-slate-900 rounded-[5rem] overflow-hidden shadow-2xl border border-slate-100 dark:border-slate-800">
            {{-- Profile Header --}}
            <div class="h-48 bg-emerald-900 relative">
                <div class="absolute -bottom-16 left-12">
                    <div class="w-32 h-32 bg-white dark:bg-slate-950 p-2 rounded-[2.5rem] shadow-2xl">
                        <div class="w-full h-full bg-emerald-500 rounded-[2rem] flex items-center justify-center text-white text-5xl font-black italic uppercase">
                            {{ substr($user->nama_user, 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-20 px-12 pb-12">
                <div class="flex justify-between items-start mb-12">
                    <div>
                        <h2 class="text-4xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">{{ $user->nama_user }}</h2>
                        <p class="text-emerald-600 font-black text-xs uppercase tracking-[0.3em] mt-2 italic">{{ $user->role }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('direktur.manajemen_user.edit', $user->id_user) }}" class="px-6 py-3 bg-emerald-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest">Modify</a>
                        <form action="{{ route('direktur.manajemen_user.destroy', $user->id_user) }}" method="POST" onsubmit="return confirm('Archive this user?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-6 py-3 bg-red-100 text-red-600 rounded-2xl font-black text-[10px] uppercase tracking-widest">Archive</button>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-12">
                    <div class="space-y-4">
                        <h4 class="text-slate-400 font-black text-[10px] uppercase tracking-widest">Internal Username</h4>
                        <p class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ $user->username }}</p>
                    </div>
                    <div class="space-y-4">
                        <h4 class="text-slate-400 font-black text-[10px] uppercase tracking-widest">Security Status</h4>
                        <span class="inline-block px-4 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-lg uppercase tracking-tighter italic">Authorized & Verified</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection