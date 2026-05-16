@extends('layouts.app')

@section('title', 'Modify Identity | Control Center')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate__animated animate__fadeInUp">
    {{-- Header Navigation --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Modify <span class="text-amber-500">Identity</span>
            </h1>
            <p class="text-[10px] font-bold text-slate-500 dark:text-emerald-500/60 uppercase tracking-[0.3em] mt-1">
                SEDEKAH • Amending Registry Matrix for #{{ str_pad($user->id_user, 4, '0', STR_PAD_LEFT) }} 🛠️
            </p>
        </div>
        <a href="{{ route('direktur.manajemen_user.index') }}" 
            class="flex items-center gap-3 px-6 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest hover:border-amber-500 hover:text-amber-500 transition-all shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali
        </a>
    </div>

    {{-- Form Container --}}
    <div class="bg-white dark:bg-slate-900 rounded-[3.5rem] border border-amber-100/50 dark:border-amber-900/10 shadow-2xl overflow-hidden relative">
        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        
        <div class="p-8 md:p-14 relative z-10">
            <form action="{{ route('direktur.manajemen_user.update', $user->id_user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Full Name Input --}}
                <div class="group space-y-2">
                    <label class="text-[10px] font-black text-slate-400 dark:text-amber-600/50 uppercase tracking-[0.3em] ml-2 group-focus-within:text-amber-500 transition-colors">Full Operational Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-amber-500 transition-colors">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <input type="text" name="nama_user" value="{{ old('nama_user', $user->nama_user) }}" required 
                            class="w-full pl-14 pr-6 py-5 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent rounded-3xl text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-900 transition-all uppercase placeholder:normal-case @error('nama_user') border-red-500 @enderror"
                            placeholder="John Doe">
                    </div>
                    @error('nama_user') <p class="text-[10px] font-bold text-red-500 ml-2 uppercase tracking-wider">{{ $message }}</p> @enderror
                </div>

                {{-- Username & Password Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Username --}}
                    <div class="group space-y-2">
                        <label class="text-[10px] font-black text-slate-400 dark:text-amber-600/50 uppercase tracking-[0.3em] ml-2 group-focus-within:text-amber-500 transition-colors">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-amber-500 transition-colors">
                                <i class="fas fa-at"></i>
                            </div>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" required 
                                class="w-full pl-14 pr-6 py-5 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent rounded-3xl text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-900 transition-all @error('username') border-red-500 @enderror"
                                placeholder="johndoe_">
                        </div>
                        @error('username') <p class="text-[10px] font-bold text-red-500 ml-2 uppercase tracking-wider">{{ $message }}</p> @enderror
                    </div>

                    {{-- New Password (Optional) --}}
                    <div class="group space-y-2">
                        <label class="text-[10px] font-black text-slate-400 dark:text-amber-600/50 uppercase tracking-[0.3em] ml-2 group-focus-within:text-amber-500 transition-colors">New Password <span class="text-slate-300 font-normal lowercase">(Leave blank if unchanged)</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-amber-500 transition-colors">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input type="password" name="password" id="password" 
                                class="w-full pl-14 pr-14 py-5 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent rounded-3xl text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-900 transition-all @error('password') border-red-500 @enderror"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePassword('password', 'toggle-icon')" 
                                class="absolute inset-y-0 right-0 pr-6 flex items-center text-slate-300 hover:text-amber-500 transition-colors">
                                <i id="toggle-icon" class="fas fa-eye text-xs"></i>
                            </button>
                        </div>
                        @error('password') <p class="text-[10px] font-bold text-red-500 ml-2 uppercase tracking-wider">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Role Selection --}}
                <div class="group space-y-2">
                    <label class="text-[10px] font-black text-slate-400 dark:text-amber-600/50 uppercase tracking-[0.3em] ml-2 group-focus-within:text-amber-500 transition-colors">Access Privilege (Role)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-amber-500 transition-colors">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <select name="role" required 
                            class="w-full pl-14 pr-10 py-5 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent rounded-3xl text-sm font-black uppercase italic text-amber-600 dark:text-amber-400 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-900 transition-all appearance-none cursor-pointer">
                            <option value="administrator" {{ old('role', $user->role) === 'administrator' ? 'selected' : '' }}>ADMINISTRATOR</option>
                            <option value="direktur" {{ old('role', $user->role) === 'direktur' ? 'selected' : '' }}>DIREKTUR</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    @error('role') <p class="text-[10px] font-bold text-red-500 ml-2 uppercase tracking-wider">{{ $message }}</p> @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="pt-10 flex flex-col md:flex-row gap-4">
                    <button type="submit" id="btn-submit" 
                        class="flex-[2] bg-amber-500 text-white py-5 rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-amber-900/20 hover:bg-amber-600 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                        <i class="fas fa-user-check"></i> Commit Modifications
                    </button>
                    <a href="{{ route('direktur.manajemen_user.index') }}" 
                        class="flex-1 px-8 py-5 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] hover:bg-red-500 hover:text-white dark:hover:bg-red-600 dark:hover:text-white transition-all text-center flex items-center justify-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    const form = document.querySelector('form');
    const btnSubmit = document.getElementById('btn-submit');
    form.onsubmit = function() {
        btnSubmit.disabled = true;
        btnSubmit.classList.add('opacity-70', 'cursor-not-allowed');
        btnSubmit.innerHTML = `
            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Committing Registry...
        `;
    };
</script>
@endsection