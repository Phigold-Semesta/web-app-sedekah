<!DOCTYPE html>
<html lang="id" 
    x-data="{ 
        darkMode: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        sidebarOpen: false,
        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.theme = this.darkMode ? 'dark' : 'light';
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }" 
    :class="darkMode ? 'dark' : ''">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title', 'Dashboard') | SEDEKAH</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        #main-sidebar { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .nav-item { display: flex; align-items: center; padding: 1rem; position: relative; transition: all 0.3s ease; color: rgba(255, 255, 255, 0.6); }
        .sidebar-active { color: #ffffff !important; font-weight: 800; background-color: rgba(255, 255, 255, 0.1); }
        .sidebar-active::before { content: ''; position: absolute; left: 0; top: 25%; height: 50%; width: 4px; background: #FFF200; border-radius: 0 4px 4px 0; }
        
        @media (min-width: 1024px) {
            #main-sidebar:not(.mobile-sidebar) { width: 88px; }
            #main-sidebar:not(.mobile-sidebar):hover { width: 288px; }
            #main-sidebar:not(.mobile-sidebar) .nav-text, #main-sidebar:not(.mobile-sidebar) .logo-full { opacity: 0; visibility: hidden; display: none; }
            #main-sidebar:not(.mobile-sidebar):hover .nav-text, #main-sidebar:not(.mobile-sidebar):hover .logo-full { opacity: 1; visibility: visible; display: flex; }
        }
    </style>
</head>
<body class="antialiased text-slate-800 bg-[#f8fafc] dark:bg-[#020d0b] dark:text-emerald-50 transition-colors duration-300">

    <div class="flex min-h-screen relative">
        <!-- Sidebar Desktop -->
        <aside id="main-sidebar" class="hidden lg:flex bg-[#065f46] h-screen text-white flex-col z-40 shadow-2xl shrink-0 overflow-hidden group">
           <div class="p-6 h-24 flex items-center shrink-0 border-b border-white/5 relative overflow-hidden group">
    
    <div class="logo-full flex items-center gap-3 transition-opacity duration-300 group-hover:opacity-0 opacity-100">
        <div class="bg-white p-2 rounded-xl shadow-lg shrink-0 flex items-center justify-center w-10 h-10">
            <i class="fas fa-hand-holding-heart text-[#046A38] text-lg"></i>
        </div>
        <div class="flex flex-col">
            <span class="font-extrabold tracking-tighter text-lg uppercase leading-none text-white">Sistem</span>
            <span class="text-emerald-400 text-[9px] font-black tracking-[0.3em] uppercase">Aplikasi Sedekah</span>
        </div>
    </div>

    <div class="icon-collapsed absolute left-6 flex items-center justify-center transition-opacity duration-300">
        <div class="bg-white p-3 rounded-2xl shadow-xl flex items-center justify-center w-12 h-12">
            <i class="fas fa-hand-holding-heart text-[#046A38] text-xl"></i>
        </div>
    </div>
    <nav class="flex-1 mt-8 overflow-y-auto custom-scrollbar">
</div>

            

            <nav class="flex-1 mt-8">
                <a href="{{ route('donatur.dashboard') }}" class="nav-item {{ request()->routeIs('donatur.dashboard') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
                    <i class="fas fa-desktop w-6 text-center"></i>
                    <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Dashboard</span>
                </a>
                <a href="{{ route('donatur.donasi.index') }}" class="nav-item {{ request()->routeIs('donatur.donasi.*') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
                    <i class="fas fa-heart w-6 text-center"></i>
                    <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Donasi</span>
                </a>
               <a href="{{ route('donatur.riwayat.index') }}" class="nav-item {{ request()->routeIs('donatur.riwayat.index') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
    <i class="fas fa-history w-6 text-center"></i>
    <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Riwayat</span>
</a>

<a href="{{ route('donatur.lacak.index') }}" class="nav-item {{ request()->routeIs('donatur.lacak.*') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
    <i class="fas fa-map-marked-alt w-6 text-center"></i>
    <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Lacak Donasi</span>
</a>

<a href="{{ route('donatur.rating.index') }}" class="nav-item {{ request()->routeIs('donatur.rating.index') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
    <i class="fas fa-star w-6 text-center"></i>
    <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Rating</span>
</a>

</nav>
            <div class="p-4 mb-4">
                <button onclick="confirmLogout(event)" class="nav-item w-full text-red-300 hover:bg-red-500/10 rounded-2xl p-4">
                    <i class="fas fa-power-off w-6 text-center"></i>
                    <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Keluar</span>
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden">
            <header class="h-20 bg-white/80 dark:bg-slate-900/80 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center px-4 sm:px-6 lg:px-10 shrink-0">
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Tombol Hamburger Mobile -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-emerald-600 dark:text-emerald-400 p-2 hover:bg-emerald-50 dark:hover:bg-slate-800 rounded-lg transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-slate-800 dark:text-white font-black text-base sm:text-lg lg:text-xl uppercase italic tracking-tighter">
                        @yield('page_title', 'Dashboard')
                    </h2>
                </div>
                
                <div class="flex items-center gap-3 lg:gap-4">
                    <!-- Tombol Dark Mode -->
                    <button @click="toggleTheme()" class="text-slate-400 hover:text-emerald-500 dark:hover:text-emerald-400 text-lg transition-colors p-2">
                        <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    </button>
                    
                    <!-- Profil Donatur Dinamis -->
                    <div class="flex items-center gap-3 pl-3 lg:pl-4 border-l border-slate-200 dark:border-slate-700">
                        <div class="text-right hidden sm:block">
                            <!-- PERBAIKAN: Mengambil data tepat dari kolom 'nama_donatur' -->
                            @php
                                $namaDonatur = Auth::guard('donatur')->user()->nama_donatur ?? 'Donatur';
                            @endphp
                            <p class="text-xs font-bold text-slate-800 dark:text-white truncate max-w-[120px] lg:max-w-[200px]">{{ $namaDonatur }}</p>
                            <p class="text-[9px] text-emerald-600 dark:text-emerald-400 font-bold uppercase">Donatur</p>
                        </div>
                        <!-- Foto Avatar Dinamis dari inisial nama -->
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($namaDonatur) }}&background=065f46&color=fff&bold=true" alt="Avatar" class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl border-2 border-emerald-500 shadow-sm object-cover">
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-10 bg-[#f8fafc] dark:bg-[#041a16] transition-colors">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Background Overlay Mobile Sidebar dengan efek fade -->
    <div x-show="sidebarOpen" x-cloak 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false" 
         class="fixed inset-0 bg-black/60 z-50 lg:hidden backdrop-blur-sm">
    </div>

    <!-- Mobile Sidebar dengan efek slide dari kiri -->
    <div x-show="sidebarOpen" x-cloak 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed left-0 top-0 h-full w-64 bg-[#065f46] z-[60] lg:hidden p-6 text-white shadow-2xl flex flex-col">
        
        <div class="flex items-center justify-between mb-8">
            <h3 class="font-black text-lg uppercase tracking-widest text-emerald-300">SEDEKAH</h3>
            <button @click="sidebarOpen = false" class="text-white hover:text-emerald-300">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <nav class="flex-1 space-y-2">
            <a href="{{ route('donatur.dashboard') }}" class="block px-4 py-3 rounded-xl font-bold {{ request()->routeIs('donatur.dashboard') ? 'bg-white/10 text-white' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                <i class="fas fa-desktop w-6"></i> Dashboard
            </a>
            <a href="{{ route('donatur.donasi.index') }}" class="block px-4 py-3 rounded-xl font-bold {{ request()->routeIs('donatur.donasi.*') ? 'bg-white/10 text-white' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                <i class="fas fa-heart w-6"></i> Donasi
            </a>
            <a href="{{ route('donatur.riwayat.index') }}" class="block px-4 py-3 rounded-xl font-bold {{ request()->routeIs('donatur.riwayat.index') ? 'bg-white/10 text-white' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                <i class="fas fa-history w-6"></i> Riwayat
            </a>
        </nav>

        <div class="mt-auto pt-4 border-t border-white/10">
            <button onclick="confirmLogout(event)" class="w-full flex items-center px-4 py-3 text-red-300 hover:bg-red-500/10 rounded-xl font-bold transition-colors">
                <i class="fas fa-power-off w-6"></i> Keluar
            </button>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

    <script>
        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'KELUAR SEDEKAH?',
                icon: 'question',
                confirmButtonColor: '#065f46',
                showCancelButton: true,
                confirmButtonText: 'YA',
                cancelButtonText: 'BATAL'
            }).then((result) => { if (result.isConfirmed) document.getElementById('logout-form').submit(); });
        }
    </script>
</body>
</html>