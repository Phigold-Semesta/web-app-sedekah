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
    <title>@yield('page_title', 'Dashboard') | Aplikasi SEDEKAH</title>
    
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
                        'emerald-dark': '#065f46',
                        'gold-accent': '#FFF200',
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Master Sidebar Animation Mechanics */
        #main-sidebar {
            width: 88px; 
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item {
            display: flex;
            align-items: center;
            position: relative;
            transition: all 0.3s ease;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Active State with Luxury Gold Accent Indicator */
        .sidebar-active {
            color: #ffffff !important;
            font-weight: 800;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 25%;
            height: 50%;
            width: 4px;
            background: #FFF200;
            border-radius: 0 4px 4px 0;
            box-shadow: 0 0 10px rgba(255, 242, 0, 0.7);
        }

        /* Desktop View Hover System */
        @media (min-width: 1024px) {
            #main-sidebar:hover { width: 288px; }
            
            #main-sidebar .nav-text, #main-sidebar .logo-full, #main-sidebar .menu-header {
                opacity: 0; visibility: hidden; display: none;
            }
            
            #main-sidebar:hover .nav-text, #main-sidebar:hover .logo-full, #main-sidebar:hover .menu-header {
                opacity: 1; visibility: visible; display: flex; 
            }

            #main-sidebar:hover .menu-header { display: block; }

            #main-sidebar .nav-item { 
                justify-content: center; 
                height: 56px;
                margin-bottom: 4px;
            }

            #main-sidebar:hover .nav-item { 
                justify-content: flex-start; 
                padding-left: 2rem;
                width: 100%;
            }
        }

        /* Mobile Responsive System */
        @media (max-width: 1024px) {
            #main-sidebar { position: fixed; left: -100%; width: 280px; }
            #main-sidebar.show-sidebar { left: 0; }
            #main-sidebar .nav-text, #main-sidebar .logo-full, #main-sidebar .menu-header { display: flex; opacity: 1; visibility: visible; }
            #main-sidebar .icon-collapsed { display: none; }
            #main-sidebar .nav-item { width: 100%; justify-content: flex-start; padding-left: 2rem; height: 56px; }
        }

        /* Custom Premium Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.15); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.3); }
    </style>
</head>
<body class="antialiased text-slate-800 bg-[#f8fafc] dark:bg-[#020d0b] dark:text-emerald-50 transition-colors duration-300">

    <div id="sidebar-overlay" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-slate-900/60 z-30 hidden backdrop-blur-sm transition-opacity"></div>

    <div class="flex min-h-screen relative overflow-hidden">
        
        <aside id="main-sidebar" class="bg-[#065f46] h-screen text-white flex flex-col z-40 shadow-2xl shrink-0 overflow-hidden group">
            
            <div class="p-6 h-24 flex items-center shrink-0 border-b border-white/5">
                <div class="logo-full flex items-center gap-3">
                    <div class="bg-white p-2 rounded-xl shadow-lg shrink-0 flex items-center justify-center">
                        <i class="fas fa-hand-holding-heart text-emerald-primary text-lg"></i>
                    </div>
                    <span class="font-extrabold tracking-tighter text-lg uppercase leading-none text-white">Sistem<br><span class="text-emerald-400 text-[9px] font-black tracking-[0.3em]">APLIKASI SEDEKAH</span></span>
                </div>

                <div class="icon-collapsed w-full flex justify-center group-hover:hidden">
                    <div class="bg-white p-3 rounded-2xl shadow-xl text-center flex items-center justify-center">
                        <i class="fas fa-hand-holding-heart text-emerald-primary text-2xl"></i>
                    </div>
                </div>
            </div>

            <nav class="flex-1 mt-8 overflow-y-auto custom-scrollbar">
                
                {{-- --- MENU LENGKAP AKTOR ADMINISTRATOR --- --}}
                @if(Auth::user()->role == 'administrator')
                    <div class="menu-header px-8 py-3 text-[10px] font-black text-white/30 uppercase tracking-[0.25em]">Operasional Admin</div>
                    
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-desktop w-6 text-center text-sm"></i>
                        <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Dashboard Operasional</span>
                    </a>

                    <a href="{{ route('admin.donatur.index') }}" class="nav-item {{ request()->routeIs('admin.donatur.*') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-user-friends w-6 text-center text-sm"></i>
                        <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Kelola Data Donatur</span>
                    </a>

                    <a href="{{ route('admin.rating_kunjungan.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.rating_kunjungan.*') ? 'text-white bg-white/10 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }} flex items-center px-4 py-3 transition-all duration-200">
                        <i class="fas fa-comments w-6 text-center text-sm {{ request()->routeIs('admin.rating_kunjungan.*') ? 'text-yellow-400' : '' }}"></i>
                        <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Rating Kunjungan</span>
                    </a>

                    <a href="{{ route('admin.verifikasi.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.verifikasi.*') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-clipboard-check w-6 text-center text-sm"></i>
                        <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Verifikasi Donasi</span>
                    </a>

                    <a href="{{ route('admin.kategori_barang.index') }}" class="nav-item {{ request()->routeIs('admin.kategori_barang.*') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-tags w-6 text-center text-sm"></i>
                        <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Kategori Barang</span>
                    </a>

                    <a href="{{ route('admin.riwayat_donasi.index') }}" class="nav-item {{ request()->routeIs('admin.riwayat_donasi.*') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-history w-6 text-center text-sm"></i>
                        <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Riwayat Kelola Donasi</span>
                    </a>
                    
                    <a href="{{ route('admin.manajemen_user.index') }}" class="nav-item {{ request()->routeIs('admin.manajemen_user.*') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-user-cog w-6 text-center text-sm"></i>
                        <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Manajemen User</span>
                    </a>

                    <a href="{{ route('admin.audit_log.index') }}" class="nav-item {{ request()->routeIs('admin.audit_log.index') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-file-signature w-6 text-center text-sm"></i>
                        <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Audit Log Admin</span>
                    </a>
                @endif

                {{-- --- MENU LENGKAP AKTOR DIREKTUR --- --}}
                @if(Auth::user()->role == 'direktur')
                    <div class="menu-header px-8 py-3 text-[10px] font-black text-white/30 uppercase tracking-[0.25em]">Panel Eksekutif</div>

                    <a href="{{ route('direktur.dashboard') }}" class="nav-item {{ request()->routeIs('direktur.dashboard') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-chart-pie w-6 text-center text-sm"></i>
                        <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Dashboard Eksekutif</span>
                    </a>

                    <a href="{{ route('direktur.riwayat_donatur.index') }}" class="nav-item {{ request()->routeIs('direktur.riwayat_donatur.*') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-user-shield w-6 text-center text-sm"></i>
                        <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Monitoring Donatur</span>
                    </a>

                    <a href="{{ route('direktur.keuangan.index') }}" 
                       class="nav-item {{ request()->routeIs('direktur.keuangan.*') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-file-invoice-dollar w-6 text-center text-sm"></i>
                        <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Laporan Keuangan</span>
                    </a>

                    <a href="{{ route('direktur.logistik.index') }}" 
                       class="nav-item {{ request()->routeIs('direktur.logistik.*') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-boxes w-6 text-center text-sm"></i>
                        <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Laporan Logistik</span>
                    </a>

                    <a href="{{ route('direktur.manajemen_user.index') }}" class="nav-item {{ request()->routeIs('direktur.manajemen_user.*') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-user-cog w-6 text-center text-sm"></i>
                        <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Manajemen User</span>
                    </a>

                    <a href="{{ route('direktur.audit') }}" 
                       class="nav-item {{ request()->routeIs('direktur.audit') ? 'sidebar-active' : 'hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-shield-alt w-6 text-center text-sm"></i>
                        <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Audit Log</span>
                    </a>
                @endif
                
            </nav>

            <div class="p-4 mb-4 shrink-0">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                <button onclick="confirmLogout(event)" 
                    class="nav-item w-full h-12 flex items-center justify-center lg:group-hover:justify-start lg:group-hover:px-8 text-red-400 hover:bg-red-500/10 rounded-2xl transition-all">
                    <i class="fas fa-power-off w-6 text-center text-sm"></i>
                    <span class="nav-text ml-4 text-[11px] font-black uppercase tracking-widest">Keluar Sistem</span>
                </button>
            </div>
        </aside>

        <main class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
            
            <header class="h-20 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-100 dark:border-slate-800 flex justify-between items-center px-8 z-20 shrink-0 transition-all">
                <div class="flex items-center gap-4">
                    <button onclick="toggleMobileSidebar()" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl bg-emerald-50 text-emerald-primary active:scale-90 transition-transform">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="flex flex-col">
                        <p class="text-[10px] font-black text-emerald-600 dark:text-emerald-500 uppercase tracking-[0.3em] italic leading-none mb-1">Yayasan Rumah Harapan </p>
                        <h2 class="text-slate-800 dark:text-white font-black text-xl tracking-tighter uppercase italic">
                            @yield('page_title', 'Dashboard')
                        </h2>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <button @click="toggleTheme()" class="text-slate-400 hover:text-emerald-500 transition-colors">
                        <i x-show="darkMode" class="fa-solid fa-sun text-lg"></i>
                        <i x-show="!darkMode" class="fa-solid fa-moon text-lg"></i>
                    </button>

                    <div class="flex items-center gap-3 pl-6 border-l border-slate-100 dark:border-slate-800">
                        <div class="text-right hidden sm:block leading-tight">
                            <p class="text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-tight">{{ Auth::user()->nama_user }}</p>
                            <p class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest italic">{{ Auth::user()->role }}</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama_user) }}&background=008f5d&color=fff&bold=true" class="w-10 h-10 rounded-xl border-2 border-white dark:border-slate-700 shadow-md">
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-6 md:p-10 bg-[#f8fafc] dark:bg-[#041a16] transition-colors duration-300">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('show-sidebar');
            overlay.classList.toggle('hidden');
        }

        function confirmLogout(event) {
            event.preventDefault();
            const isDark = document.documentElement.classList.contains('dark');
            Swal.fire({
                title: 'KELUAR SISTEM?',
                text: "Sesi Anda akan segera berakhir.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#008f5d',
                cancelButtonColor: isDark ? '#334155' : '#f1f5f9',
                confirmButtonText: 'YA, KELUAR',
                cancelButtonText: 'BATAL',
                reverseButtons: true,
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f1f5f9' : '#1e293b',
                customClass: {
                    popup: 'rounded-[2.5rem]',
                    confirmButton: 'rounded-2xl font-black text-[10px] tracking-widest px-8 py-3',
                    cancelButton: 'rounded-2xl font-black text-[10px] tracking-widest px-8 py-3 text-slate-500'
                }
            }).then((result) => {
                if (result.isConfirmed) { 
                    document.getElementById('logout-form').submit(); 
                }
            });
        }
    </script>
</body>
</html>