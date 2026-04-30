@extends('layouts.app')

@section('title', 'Modify Identity | SEDEKAH')

@section('content')
<div class="p-4 md:p-10 bg-slate-50 dark:bg-slate-950 min-h-screen flex justify-center items-center">
    <div class="w-full max-w-2xl bg-white dark:bg-slate-900 p-12 rounded-[4rem] shadow-2xl border-t-8 border-emerald-600">
        <h2 class="text-3xl font-black text-emerald-900 dark:text-emerald-400 uppercase italic tracking-tighter mb-10">Credential Modification</h2>
        
        <form action="{{ route('direktur.manajemen_user.update', $user->id_user) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Full Legal Name</label>
                <input type="text" name="nama_user" value="{{ $user->nama_user }}" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-6 py-4 text-sm font-bold uppercase">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Username</label>
                <input type="text" name="username" value="{{ $user->username }}" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-6 py-4 text-sm font-bold">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">New Password (Leave blank to keep current)</label>
                <input type="password" name="password" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-6 py-4 text-sm font-bold" placeholder="••••••••">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Access Privilege</label>
                <select name="role" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-6 py-4 text-sm font-black uppercase italic text-emerald-600">
                    <option value="direktur" {{ $user->role == 'direktur' ? 'selected' : '' }}>DIREKTUR</option>
                    <option value="administrator" {{ $user->role == 'administrator' ? 'selected' : '' }}>ADMINISTRATOR</option>
                    <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>STAFF OPERASIONAL</option>
                </select>
            </div>

            <div class="pt-6 flex gap-4">
                <button type="submit" class="flex-1 bg-emerald-600 text-white py-4 rounded-3xl font-black text-xs uppercase tracking-widest shadow-xl transition-all hover:bg-emerald-700">Update Identity</button>
                <a href="{{ route('direktur.manajemen_user.index') }}" class="px-8 py-4 bg-slate-100 dark:bg-slate-800 text-slate-400 rounded-3xl font-black text-xs uppercase tracking-widest text-center">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection