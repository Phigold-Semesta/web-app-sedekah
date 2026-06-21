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
            <div class="p-6 h-24 flex items-center shrink-0 border-b border-white/5">
                <div class="logo-full flex items-center gap-3">
                    <div class="bg-white p-2 rounded-xl text-emerald-700"><i class="fas fa-hand-holding-heart text-lg"></i></div>
                    <span class="font-extrabold text-lg uppercase leading-none">SEDEKAH<br><span class="text-emerald-300 text-[9px] tracking-[0.2em]">DONATUR</span></span>
                </div>
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
            <header class="h-20 bg-white/80 dark:bg-slate-900/80 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center px-6 lg:px-10 shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-emerald-600 dark:text-emerald-400">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-slate-800 dark:text-white font-black text-lg lg:text-xl uppercase italic tracking-tighter">
                        @yield('page_title', 'Dashboard')
                    </h2>
                </div>
                
                <div class="flex items-center gap-3 lg:gap-4">
                    <button @click="toggleTheme()" class="text-slate-400 hover:text-emerald-500 dark:hover:text-emerald-400 text-lg transition-colors">
                        <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    </button>
                    <div class="flex items-center gap-3 pl-3 lg:pl-4 border-l border-slate-200 dark:border-slate-700">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-slate-800 dark:text-white">{{ Auth::user()->name ?? 'Donatur' }}</p>
                            <p class="text-[9px] text-emerald-600 dark:text-emerald-400 font-bold uppercase">Donatur</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}&background=065f46&color=fff" class="w-9 h-9 rounded-xl border-2 border-emerald-500">
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-6 lg:p-10 bg-[#f8fafc] dark:bg-[#041a16] transition-colors">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Mobile Sidebar -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-50 lg:hidden"></div>
    <div x-show="sidebarOpen" x-cloak class="fixed left-0 top-0 h-full w-64 bg-[#065f46] z-[60] lg:hidden p-6 text-white shadow-2xl">
        <h3 class="font-black text-lg mb-8 uppercase">SEDEKAH Menu</h3>
        <a href="{{ route('donatur.dashboard') }}" class="block py-4 font-bold border-b border-white/10">Dashboard</a>
        <a href="{{ route('donatur.donasi.index') }}" class="block py-4 font-bold border-b border-white/10">Donasi</a>
        <a href="{{ route('donatur.riwayat.index') }}" class="block py-4 font-bold border-b border-white/10">Riwayat</a>
        <button onclick="confirmLogout(event)" class="block w-full text-left py-4 mt-4 text-red-300 font-bold">Keluar</button>
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