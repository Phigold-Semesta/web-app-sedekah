<!DOCTYPE html>
<html lang="id" x-data="{ 
    darkMode: localStorage.getItem('theme') === 'dark',
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
    }
}" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Aplikasi SEDEKAH</title>
    
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'emerald-primary': '#008f5d',
                        'gold-accent': '#b45309',
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; transition: background-color 0.3s ease; }
        
        .sidebar-active {
            background-color: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        #main-sidebar {
            width: 88px; 
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @media (min-width: 1024px) {
            #main-sidebar:hover { width: 288px; }
            #main-sidebar .nav-text, #main-sidebar .logo-full, #main-sidebar .menu-header {
                opacity: 0; display: none; transition: opacity 0.3s ease;
            }
            #main-sidebar:hover .nav-text, #main-sidebar:hover .logo-full, #main-sidebar:hover .menu-header {
                opacity: 1; display: flex;
            }
            #main-sidebar .icon-collapsed { display: flex; }
            #main-sidebar:hover .icon-collapsed { display: none; }
            #main-sidebar .nav-item { justify-content: center; }
            #main-sidebar:hover .nav-item { justify-content: flex-start; }
        }

        @media (max-width: 1024px) {
            #main-sidebar { position: fixed; left: -100%; width: 280px; }
            #main-sidebar.show-sidebar { left: 0; }
            #main-sidebar .nav-text, #main-sidebar .logo-full { display: flex; opacity: 1; }
            #main-sidebar .icon-collapsed { display: none; }
        }

        .swal2-popup { border-radius: 2rem !important; padding: 2rem !important; }
    </style>
</head>
<body class="antialiased text-slate-800 bg-[#f0f9f4] dark:bg-[#06201b] dark:text-emerald-50 transition-colors duration-300">

    <div id="sidebar-overlay" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-slate-900/40 z-30 hidden backdrop-blur-sm"></div>

    <div class="flex min-h-screen relative overflow-hidden">
        
        <!-- SIDEBAR -->
        <aside id="main-sidebar" class="bg-emerald-primary dark:bg-emerald-900 h-screen text-white flex flex-col z-40 shadow-2xl shrink-0 overflow-hidden group">
            
            <!-- Logo Section -->
            <div class="p-6 h-24 flex items-center border-b border-white/10 shrink-0">
                <div class="logo-full items-center gap-3">
                    <div class="bg-white p-2 rounded-xl shadow-lg shrink-0">
                        <i class="fas fa-hand-holding-heart text-emerald-primary text-lg"></i>
                    </div>
                    <span class="font-extrabold tracking-tighter text-lg uppercase leading-none">Aplikasi<br><span class="text-emerald-300 text-xs text-nowrap font-bold tracking-widest">SEDEKAH SYSTEM</span></span>
                </div>

                <div class="icon-collapsed w-full justify-center">
                    <div class="bg-white p-3 rounded-2xl shadow-xl border-4 border-emerald-400/30 text-center">
                        <i class="fas fa-hand-holding-heart text-emerald-primary text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Navigation Section -->
            <nav class="flex-1 px-4 mt-6 overflow-y-auto custom-scrollbar space-y-2">
                
                @if(Auth::user()->role == 'administrator')
                    <div class="menu-header px-4 py-3 text-[10px] font-black text-emerald-200/50 uppercase tracking-[0.2em]">Operasional Admin</div>
                    
                    <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-desktop w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Dashboard Operasional</span>
                    </a>

                    <a href="{{ route('admin.verifikasi') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ request()->routeIs('admin.verifikasi') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-clipboard-check w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Verifikasi Donasi</span>
                    </a>

                    <a href="{{ route('admin.riwayat') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ request()->routeIs('admin.riwayat') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-list-ul w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Kelola Riwayat</span>
                    </a>

                    <a href="{{ route('admin.donatur') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ request()->routeIs('admin.donatur') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-address-book w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Data Donatur</span>
                    </a>

                    <a href="{{ route('admin.audit') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ request()->routeIs('admin.audit') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-fingerprint w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Monitoring Audit Log</span>
                    </a>
                @endif

                @if(Auth::user()->role == 'direktur')
                    <div class="menu-header px-4 py-3 text-[10px] font-black text-emerald-200/50 uppercase tracking-[0.2em]">Panel Eksekutif</div>

                    <a href="{{ route('direktur.dashboard') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ request()->routeIs('direktur.dashboard') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-chart-pie w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Dashboard Eksekutif</span>
                    </a>

                    <a href="{{ route('direktur.riwayat_donatur') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ request()->routeIs('direktur.riwayat_donatur') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-user-shield w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Monitoring Donatur</span>
                    </a>

                    <a href="{{ route('direktur.laporan') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ request()->routeIs('direktur.laporan') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-file-invoice-dollar w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Laporan Donasi & Keuangan</span>
                    </a>

                    <a href="{{ route('direktur.logistik') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ request()->routeIs('direktur.logistik') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-boxes w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Laporan Logistik</span>
                    </a>

                    <a href="{{ route('direktur.users') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ request()->routeIs('direktur.users') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-user-cog w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Manajemen User</span>
                    </a>
                @endif
                
            </nav>

            <!-- Bottom Action -->
            <div class="p-4 mb-4">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                <button onclick="confirmLogout(event)" 
                    class="nav-item w-full flex items-center justify-center py-4 px-6 rounded-2xl bg-white/5 hover:bg-red-500 hover:shadow-lg text-red-100 transition-all border border-white/5 group">
                    <i class="fas fa-power-off w-6 text-center group-hover:scale-110 text-sm"></i>
                    <span class="nav-text ml-3 text-sm font-bold tracking-widest uppercase text-nowrap">Logout</span>
                </button>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
            <!-- Header/Navbar -->
            <header class="h-20 bg-white dark:bg-emerald-900 border-b border-emerald-50 dark:border-emerald-800 shadow-sm flex justify-between items-center px-8 z-20 shrink-0 transition-colors duration-300">
                <div class="flex items-center gap-4">
                    <button onclick="toggleMobileSidebar()" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-800 text-emerald-primary">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="flex flex-col leading-none">
                        <h2 class="text-slate-800 dark:text-white font-black text-xl tracking-tighter uppercase italic">@yield('page_title', 'Dashboard')</h2>
                        <p class="text-[9px] font-bold text-slate-400 dark:text-emerald-400/60 uppercase tracking-[0.25em] mt-1.5 hidden sm:block">Aplikasi SEDEKAH • DNA Project</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Dark Mode Toggle -->
                    <button @click="toggleTheme()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-emerald-800 text-slate-500 dark:text-yellow-400 border border-slate-100 dark:border-emerald-700 transition-all hover:scale-110">
                        <i x-show="darkMode" class="fa-solid fa-sun text-lg" x-cloak></i>
                        <i x-show="!darkMode" class="fa-solid fa-moon text-lg" x-cloak></i>
                    </button>

                    <div class="flex items-center gap-4 bg-slate-50 dark:bg-emerald-800/50 py-1.5 pl-4 pr-1.5 rounded-2xl border border-slate-100 dark:border-emerald-700">
                        <div class="text-right leading-tight hidden md:block">
                            <p class="text-xs font-black text-slate-800 dark:text-emerald-50 uppercase tracking-tighter">{{ Auth::user()->nama_user }}</p>
                            <div class="flex items-center justify-end gap-1.5 mt-0.5">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse shadow-lg"></span>
                                <p class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest italic leading-none">{{ strtoupper(Auth::user()->role) }}</p>
                            </div>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama_user) }}&background=008f5d&color=fff&bold=true" class="w-10 h-10 rounded-xl border-2 border-white dark:border-emerald-700 shadow-sm">
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar bg-[#f8fafc] dark:bg-[#041a16]">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleMobileSidebar() {
            document.getElementById('main-sidebar').classList.toggle('show-sidebar');
            document.getElementById('sidebar-overlay').classList.toggle('hidden');
        }

        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Yakin keluar?',
                text: "Sesi Anda akan segera diakhiri.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#008f5d',
                cancelButtonColor: '#f1f5f9',
                confirmButtonText: 'YA, KELUAR',
                cancelButtonText: 'BATAL',
                reverseButtons: true,
                background: document.documentElement.classList.contains('dark') ? '#064e3b' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#ecfdf5' : '#1e293b',
            }).then((result) => {
                if (result.isConfirmed) { document.getElementById('logout-form').submit(); }
            });
        }
    </script>
</body>
</html>