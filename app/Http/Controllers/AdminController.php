<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\AuditLog; // Ditambahkan agar data log bisa dipanggil secara dinamis
use App\Models\User; // Ditambahkan untuk memanipulasi data tabel user secara dinamis
use App\Models\Donatur; // DISINKRONKAN: Memanggil Model Donatur tanpa huruf S jamak di ujung kata
use Illuminate\Support\Facades\Hash; // Ditambahkan untuk enkripsi password manual SOWAN v2
use Illuminate\Support\Facades\Auth; // Ditambahkan untuk proteksi delete-self akun aktif
use Illuminate\Support\Facades\DB; // DIKUKUHKAN: Untuk mengelola query tabel donatur secara aman

class AdminController extends Controller
{
    /**
     * Dashboard Utama Administrator.
     * Menampilkan ringkasan statistik donasi dan kunjungan.
     */
    public function index(): View
    {
        return view('admin.dashboard');
    }

    /**
     * Validasi Bukti Transaksi.
     * Menampilkan daftar donasi yang masuk dan perlu diverifikasi admin.
     */
    public function verifikasi(): View
    {
        // Pastikan file resources/views/admin/verifikasi.blade.php sudah ada
        return view('admin.verifikasi');
    }

    /**
     * Penyaluran Hibah.
     * Mengelola distribusi bantuan berupa barang atau uang ke penerima.
     */
    public function distribusi(): View
    {
        return view('admin.distribusi');
    }

    /**
     * Riwayat Transaksi.
     * Menampilkan log keseluruhan donasi yang sudah selesai atau diproses.
     */
    public function riwayat(): View
    {
        return view('admin.riwayat');
    }

    /**
     * Master Data Donatur.
     * DISEMPURNAKAN & DIHIDUPKAN: Mengelola data profil donatur secara dinamis dengan fitur Search dan Pagination.
     * REVISI DIREKTORI VIEW: Diarahkan dengan presisi ke folder 'admin/kelola_donatur/index.blade.php'
     * PENYELARASAN: Mengubah nama variabel penampung menjadi $donaturs agar terbaca oleh komponen view Blade.
     */
    public function donatur(Request $request): View
    {
        // 1. Ambil parameter filter pencarian dan jumlah baris dari view Blade
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        // 2. Bangun query builder melalui Eloquent Model 'Donatur' tanpa huruf s jamak
        $query = Donatur::query();

        // 3. Logika Fitur Pencarian (Search) berdasarkan Nama, No HP, atau Alamat
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_donatur', 'LIKE', "%{$search}%")
                  ->orWhere('no_hp', 'LIKE', "%{$search}%")
                  ->orWhere('alamat', 'LIKE', "%{$search}%");
            });
        }

        // 4. Logika Pagination / Menampilkan seluruh data sesuai kebutuhan filter
        // DISESUAIKAN: Nama variabel penampung diganti menjadi $donaturs agar sinkron dengan @forelse($donaturs)
        if ($perPage === 'all') {
            $donaturs = $query->orderBy('id_donatur', 'desc')->paginate($query->count() ?: 10)->appends($request->query());
        } else {
            $donaturs = $query->orderBy('id_donatur', 'desc')->paginate((int)$perPage)->appends($request->query());
        }

        // Mengembalikan view utama dengan membawa variabel $donaturs agar klop dengan @forelse di Blade
        return view('admin.kelola_donatur.index', compact('donaturs'));
    }

    /**
     * Tampilan Detail Profil Donatur (Include dari Use Case Kelola Data Donatur).
     * DISEMPURNAKAN: Menangani pencarian entitas tunggal donatur berdasarkan id_donatur melalui Eloquent.
     * REVISI DIREKTORI VIEW: Diarahkan dengan presisi ke folder 'admin/kelola_donatur/show.blade.php'
     */
    public function donatur_show($id_donatur): View|RedirectResponse
    {
        $donatur = Donatur::where('id_donatur', $id_donatur)->first();

        if (!$donatur) {
            return redirect()->route('admin.donatur.index')->with('error', 'Data identitas profil donatur tidak ditemukan!');
        }

        return view('admin.kelola_donatur.show', compact('donatur'));
    }

    /**
     * Menghapus Data Entitas Donatur dari Sistem.
     * DISEMPURNAKAN: Dilengkapi proteksi integritas tabel data kunjungan.
     */
    public function donatur_destroy($id_donatur): RedirectResponse
    {
        try {
            // Proteksi Integritas Database: Cek apakah donatur memiliki relasi transaksi di tabel kunjungan/donasi
            $hasRelations = DB::table('kunjungan')->where('id_donatur', $id_donatur)->exists();
            if ($hasRelations) {
                return redirect()->route('admin.donatur.index')->with('error', 'Gagal! Data donatur tidak dapat dihapus karena memiliki keterikatan riwayat kunjungan atau transaksi.');
            }

            $donatur = Donatur::where('id_donatur', $id_donatur)->first();
            if ($donatur) {
                $donatur->delete();
            }
            
            return redirect()->route('admin.donatur.index')->with('success', 'Data entitas donatur berhasil dihapus permanen dari sistem.');
        } catch (\Exception $e) {
            return redirect()->route('admin.donatur.index')->with('error', 'Terjadi kesalahan sistem saat mencoba menghapus data.');
        }
    }

    /**
     * Master Data Kategori.
     * Mengelola kategori donasi (Zakat, Infak, Sedekah, atau kategori barang).
     */
    public function kategori(): View
    {
        return view('admin.kategori');
    }

    /**
     * Monitoring Jejak Audit.
     * DISEMPURNAKAN & DIPERBAIKI: Menangani filter per_page, search, dan menyesuaikan nama variabel dengan Blade.
     */
    public function audit(Request $request): View
    {
        // 1. Ambil input pencarian dan filter baris dari form Blade
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // Default 10 baris sesuai form filter Anda

        // 2. Query dasar: Mengambil data log audit terbaru beserta data user pelaksana aksi
        $query = AuditLog::with('user')->orderBy('waktu_log', 'desc');

        // 3. Logika Fitur Pencarian (Search) agar form cari di view berfungsi
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('aksi_log', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('nama_user', 'like', '%' . $search . '%')
                               ->orWhere('role', 'like', '%' . $search . '%');
                  });
            });
        }

        // 4. Logika Penomoran Halaman (Pagination / All Data) sesuai pilihan select box
        if ($perPage === 'all') {
            $audit_list = $query->paginate($query->count() ?: 10)->appends($request->query());
        } else {
            $audit_list = $query->paginate((int)$perPage)->appends($request->query());
        }

        // PERBAIKAN UTAMA: Nama variabel disesuaikan menjadi 'audit_list' agar sinkron dengan view Blade Anda
        return view('admin.audit_log.index', compact('audit_list'));
    }

    /**
     * Halaman Utama Manajemen User (Index).
     * DISEMPURNAKAN: Menampilkan daftar pengguna dengan filter pencarian, role, dan pagination dinamis.
     * PERBAIKAN: Mengubah nama variabel dari $users menjadi $user_list agar cocok dengan looping @forelse($user_list) di Blade.
     */
    public function user_index(Request $request): View
    {
        // 1. Ambil Parameter Input Filter & Pencarian dari Form
        $search = $request->input('search');
        $roleFilter = $request->input('role');
        $perPage = $request->input('per_page', 10);

        // 2. Hitung Ringkasan Statistik untuk Bagian Atas View (Stat Badges)
        // PERBAIKAN KLOP DB: Menyesuaikan counter statistik agar sinkron dengan opsi Enum database Anda (tanpa petugas)
        $stats = [
            'total' => User::count(),
            'admin' => User::where('role', 'administrator')->count(),
            'direktur' => User::where('role', 'direktur')->count(),
        ];

        // 3. Bangun Query Utama Data Pengguna menggunakan Primary Key id_user
        $query = User::query()->orderBy('id_user', 'desc');

        // Filter Pencarian Teks (Nama atau Username)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_user', 'like', '%' . $search . '%')
                  ->orWhere('username', 'like', '%' . $search . '%'); // DISESUAIKAN: Menggunakan username (bukan email) sesuai database
            });
        }

        // Filter Berdasarkan Level Hak Akses (Role)
        if ($roleFilter) {
            $query->where('role', $roleFilter);
        }

        // 4. Logika Penomoran Halaman (Pagination / All Data)
        // PERBAIKAN NYATA: Mengubah nama variabel penampung dari $users menjadi $user_list agar dibaca oleh Blade
        if ($perPage === 'all') {
            $user_list = $query->paginate($query->count() ?: 10)->appends($request->query());
        } else {
            $user_list = $query->paginate((int)$perPage)->appends($request->query());
        }

        return view('admin.manajemen_user.index', compact('user_list', 'stats'));
    }

    /**
     * Form Tambah Pengguna Baru (Create).
     */
    public function user_create(): View
    {
        return view('admin.manajemen_user.create');
    }

    /**
     * Process Menyimpan Data Pengguna Baru (Store).
     * PENYEMPURNAKAN & SOLUSI UTAMA: Menggunakan manual validator catcher 
     * dan menyelaraskan validasi role dengan aturan ENUM asli phpMyAdmin ('administrator','direktur').
     */
    public function user_store(Request $request): RedirectResponse
    {
        // 1. Lakukan Validasi Secara Manual agar Alur Redirect Gagal Bisa Dikontrol Penuh
        // PERBAIKAN KLOP DB: Opsi 'petugas' dihapus dari validasi 'in' karena tidak didukung struktur ENUM database Anda
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'nama_user' => 'required|string|max:255',
            'username'  => 'required|string|unique:user,username|max:191', 
            'password'  => 'required|string|min:6',
            'role'      => 'required|in:administrator,direktur',
        ]);

        // 2. JIKA VALIDASI GAGAL: Paksa kembali ke form 'create' menggunakan rute GET yang sah & bawa input beserta pesan errornya
        if ($validator->fails()) {
            return redirect()->route('admin.manajemen_user.create')
                             ->withErrors($validator)
                             ->withInput();
        }

        // 3. JIKA VALIDASI SUKSES: Eksekusi Penyimpanan ke Database melalui Eloquent Model User
        User::create([
            'nama_user' => $request->nama_user,
            'username'  => $request->username,
            'password'  => Hash::make($request->password), // Enkripsi manual tanpa Breeze/Fortify
            'role'      => $request->role,
        ]);

        return redirect()->route('admin.manajemen_user.index')->with('success', 'Data akun user baru berhasil disimpan ke dalam sistem SOWAN.');
    }

    /**
     * Tampilan Detail Profil Pengguna (Show).
     * PERBAIKAN: Menggunakan primary key $id_user untuk pencarian entitas tunggal.
     */
    public function user_show($id_user): View
    {
        $user = User::findOrFail($id_user);
        return view('admin.manajemen_user.show', compact('user'));
    }

    /**
     * Form Edit Data & Reset Password Pengguna (Edit).
     * PERBAIKAN: Menggunakan primary key $id_user untuk pencarian entitas tunggal.
     */
    public function user_edit($id_user): View
    {
        $user = User::findOrFail($id_user);
        return view('admin.manajemen_user.edit', compact('user'));
    }

    /**
     * Proses Memperbarui Data Pengguna (Update).
     * PERBAIKAN: Menggunakan tabel 'user', key target 'username', di-ignore berdasarkan 'id_user', 
     * dan menyelaraskan aturan validasi role ENUM database.
     */
    public function user_update(Request $request, $id_user): RedirectResponse
    {
        $user = User::findOrFail($id_user);

        // 1. Validasi Input Data Form Edit User (Abaikan username milik dirinya sendiri)
        // PERBAIKAN KLOP DB: Aturan validasi role diselaraskan hanya untuk 'administrator' dan 'direktur'
        $request->validate([
            'nama_user' => 'required|string|max:255',
            // SOLUSI UTAMA: unique:nama_tabel,kolom,id_diabaikan,kolom_primary_key
            'username'  => 'required|string|max:191|unique:user,username,' . $id_user . ',id_user',
            'role'      => 'required|in:administrator,direktur',
            'password'  => 'nullable|string|min:6', // Bersifat opsional saat edit data profil
        ]);

        // 2. Perbarui Nilai Atribut Objek User
        $user->nama_user = $request->nama_user;
        $user->username = $request->username;
        $user->role = $request->role;

        // Jika password diisi pada form edit, maka lakukan update password baru
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.manajemen_user.index')->with('success', 'Data identitas akun user berhasil diperbarui.');
    }

    /**
     * Menghapus Akses Data Pengguna (Destroy).
     * PERBAIKAN: Menghapus data spesifik user dari database berdasarkan primary key id_user.
     */
    public function user_destroy($id_user): RedirectResponse
    {
        // Proteksi tingkat tinggi: Mencegah Administrator menghapus akunnya sendiri secara tidak sengaja
        if (Auth::id() == $id_user) {
            return redirect()->route('admin.manajemen_user.index')->with('error', 'Gagal! Anda dilarang keras menghapus akun Anda sendiri yang sedang digunakan saat ini.');
        }

        $user = User::findOrFail($id_user);
        $user->delete();

        return redirect()->route('admin.manajemen_user.index')->with('success', 'Akses data akun pengguna ' . $user->nama_user . ' berhasil dihapus permanen dari sistem.');
    }
}