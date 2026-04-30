@extends('layouts.app')

@section('title', 'Onboard New User | SEDEKAH')

@section('content')
<div class="p-4 md:p-10 bg-slate-50 dark:bg-slate-950 min-h-screen flex justify-center items-center">
    <div class="w-full max-w-2xl bg-white dark:bg-slate-900 p-12 rounded-[4rem] shadow-2xl border border-slate-50 dark:border-slate-800 relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-3xl font-black text-emerald-900 dark:text-emerald-400 uppercase italic tracking-tighter mb-10">Account Provisioning</h2>
            
            <form action="{{ route('direktur.manajemen_user.store') }}" method="POST" class="space-y-8">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Full Legal Name</label>
                    <input type="text" name="nama_user" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-6 py-4 text-sm font-bold focus:ring-4 focus:ring-emerald-500/20 transition-all uppercase">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Username</label>
                        <input type="text" name="username" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-6 py-4 text-sm font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Initial Password</label>
                        <input type="password" name="password" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-6 py-4 text-sm font-bold">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Access Privilege (Role)</label>
                    <select name="role" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-6 py-4 text-sm font-black uppercase italic text-emerald-600">
                        <option value="direktur">DIREKTUR</option>
                        <option value="administrator">ADMINISTRATOR</option>
                        <option value="staff">STAFF OPERASIONAL</option>
                    </select>
                </div>

                <div class="pt-6 flex gap-4">
                    <button type="submit" class="flex-1 bg-emerald-600 text-white py-4 rounded-3xl font-black text-xs uppercase tracking-widest shadow-xl shadow-emerald-900/20 hover:scale-[1.02] transition-all">Authorize User</button>
                    <a href="{{ route('direktur.manajemen_user.index') }}" class="px-8 py-4 bg-slate-100 dark:bg-slate-800 text-slate-400 rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all text-center">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection