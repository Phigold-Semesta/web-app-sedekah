@extends('layouts.app')

@section('title', 'Modify Identity | ' . $user->nama_user)

@section('content')
{{-- CSS Khusus untuk estetika Premium & Clean --}}
<style>
    input::-ms-reveal, input::-ms-clear { display: none; }
    input::-webkit-contacts-auto-fill-button, 
    input::-webkit-credentials-auto-fill-button {
        visibility: hidden; display: none !important; pointer-events: none;
    }

    .custom-focus:focus {
        border-color: #008f5d !important;
        box-shadow: 0 0 0 4px rgba(0, 143, 93, 0.1);
    }
</style>

<div class="max-w-4xl mx-auto space-y-8 animate__animated animate__fadeInUp">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic leading-none">
                Credential <span class="text-[#008f5d]">Modification</span>
            </h1>
            <p class="text-[10px] font-bold text-slate-400 dark:text-emerald-400/60 uppercase tracking-[0.3em] mt-3">
                SEDEKAH • Identity Access Management Protocol
            </p>
        </div>
        <a href="{{ route('direktur.manajemen_user.index') }}" 
            class="group flex items-center justify-center gap-3 px-6 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest hover:border-[#008f5d] hover:text-[#008f5d] transition-all shadow-sm">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform text-xs"></i> Back to Archive
        </a>
    </div>

    {{-- Main Form Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-emerald-50 dark:border-emerald-900/20 shadow-2xl shadow-emerald-900/5 dark:shadow-none overflow-hidden relative">
        {{-- Subtle background decoration --}}
        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full -mr-16 -mt-16 blur-3xl"></div>

        <form action="{{ route('direktur.manajemen_user.update', $user->id_user) }}" method="POST" class="p-8 md:p-12 space-y-10 relative z-10" autocomplete="off">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Full Name --}}
                <div class="group space-y-2">
                    <label class="text-[10px] font-black text-slate-400 dark:text-emerald-600/50 uppercase tracking-[0.3em] ml-4 group-focus-within:text-[#008f5d] transition-colors">Full Legal Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-id-card text-sm"></i>
                        </div>
                        <input type="text" name="nama_user" value="{{ old('nama_user', $user->nama_user) }}" required 
                            class="w-full pl-14 pr-6 py-5 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent rounded-3xl text-sm font-bold text-slate-700 dark:text-white custom-focus transition-all uppercase placeholder:normal-case">
                    </div>
                </div>

                {{-- Username --}}
                <div class="group space-y-2">
                    <label class="text-[10px] font-black text-slate-400 dark:text-emerald-600/50 uppercase tracking-[0.3em] ml-4 group-focus-within:text-[#008f5d] transition-colors">System Alias (Username)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-at text-sm"></i>
                        </div>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required 
                            class="w-full pl-14 pr-6 py-5 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent rounded-3xl text-sm font-bold text-slate-700 dark:text-white custom-focus transition-all">
                    </div>
                </div>

                {{-- Role Selection --}}
                <div class="group space-y-2 md:col-span-2">
                    <label class="text-[10px] font-black text-slate-400 dark:text-emerald-600/50 uppercase tracking-[0.3em] ml-4 group-focus-within:text-[#008f5d] transition-colors">Access Privilege Protocol</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-shield-halved text-sm"></i>
                        </div>
                        <select name="role" required 
                            class="w-full pl-14 pr-12 py-5 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent rounded-3xl text-sm font-black uppercase italic text-[#008f5d] custom-focus transition-all appearance-none cursor-pointer">
                            <option value="direktur" {{ $user->role == 'direktur' ? 'selected' : '' }}>DIREKTUR</option>
                            <option value="administrator" {{ $user->role == 'administrator' ? 'selected' : '' }}>ADMINISTRATOR</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-chevron-down text-[10px]"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Security Section --}}
            <div class="pt-10 border-t border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-[1px] flex-1 bg-slate-100 dark:bg-slate-800"></div>
                    <span class="text-[9px] font-black text-amber-500 uppercase tracking-[0.4em] italic px-4 py-2 bg-amber-50 dark:bg-amber-900/20 rounded-full border border-amber-100 dark:border-amber-900/30">
                        <i class="fas fa-key mr-2"></i> Security Override
                    </span>
                    <div class="h-[1px] flex-1 bg-slate-100 dark:bg-slate-800"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="group space-y-2">
                        <label class="text-[10px] font-black text-slate-400 dark:text-emerald-600/50 uppercase tracking-[0.3em] ml-4 group-focus-within:text-[#008f5d] transition-colors">New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#008f5d] transition-colors">
                                <i class="fas fa-lock-open text-sm"></i>
                            </div>
                            <input type="password" name="password" id="password" 
                                class="w-full pl-14 pr-14 py-5 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent rounded-3xl text-sm font-bold text-slate-700 dark:text-white custom-focus transition-all placeholder:text-slate-300 dark:placeholder:text-slate-600"
                                placeholder="Leave blank to retain current">
                            <button type="button" onclick="togglePassword('password', 'toggle-icon')" 
                                class="absolute inset-y-0 right-0 pr-6 flex items-center text-slate-300 hover:text-[#008f5d] transition-colors focus:outline-none">
                                <i id="toggle-icon" class="fas fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <p class="text-[11px] font-medium text-slate-400 italic leading-relaxed pl-4 border-l-2 border-slate-100 dark:border-slate-800">
                            Keep the password field empty if no changes are required. System will automatically encrypt new passwords using standard hashing protocols.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-6 flex flex-col md:flex-row gap-4">
                <button type="submit" id="btn-update" 
                    class="flex-[2] bg-[#008f5d] text-white py-5 rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-emerald-900/20 hover:bg-emerald-700 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                    <i class="fas fa-sync-alt"></i> Update Identity
                </button>
                <a href="{{ route('direktur.manajemen_user.index') }}" 
                    class="flex-1 px-8 py-5 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] hover:bg-red-500 hover:text-white transition-all text-center flex items-center justify-center">
                    Discard Changes
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    /**
     * Fitur Toggle Password Visibility
     */
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace('fa-eye', 'fa-eye-slash');
            icon.classList.add('text-[#008f5d]');
        } else {
            input.type = "password";
            icon.classList.replace('fa-eye-slash', 'fa-eye');
            icon.classList.remove('text-[#008f5d]');
        }
    }

    /**
     * Loading State saat Submit
     */
    const form = document.querySelector('form');
    const btnUpdate = document.getElementById('btn-update');

    form.onsubmit = function() {
        btnUpdate.disabled = true;
        btnUpdate.classList.add('opacity-70', 'cursor-not-allowed');
        btnUpdate.innerHTML = `
            <i class="fas fa-circle-notch fa-spin"></i> Synchronizing Identity...
        `;
    };
</script>
@endsection