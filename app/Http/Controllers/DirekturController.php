<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;

class DirekturController extends Controller
{
    public function index()
    {
        $data = [
            'totalAsetYayasan'    => 1250000000, 
            'totalDonasiTahunIni' => 450000000,
            'targetTahunan'       => 1000000000,
            'jumlahDonaturTetap'  => 85,
            'persentaseTarget'    => 45,
            'pertumbuhan'         => 12.5,
            'chartLabels'         => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            'chartData'           => [15, 28, 22, 35, 30, 45]
        ];
        return view('direktur.dashboard', $data);
    }

    /**
     * Menampilkan daftar user dengan fitur Search, Pagination, dan Filter Baris.
     */
    public function user_index(Request $request)
    {
        // 1. Ambil input pencarian dan jumlah baris per halaman
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5); // Default 5 baris

        // 2. Query dasar: ambil data user dan urutkan berdasarkan yang terbaru
        $query = User::orderBy('created_at', 'desc');

        // 3. Logika Searching: cari berdasarkan nama_user atau username
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_user', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // 4. Logika Filter Baris & Pagination
        // Jika filter dipilih 'all', ambil semua data tanpa pagination
        if ($perPage == 'all') {
            $user_list = $query->get();
        } else {
            // Jika selain 'all', gunakan pagination sesuai jumlah yang dipilih
            // appends() digunakan agar filter & search tidak hilang saat klik link halaman selanjutnya
            $user_list = $query->paginate($perPage)->appends($request->all());
        }

        return view('direktur.manajemen_user.index', compact('user_list'));
    }

    public function user_create()
    {
        return view('direktur.manajemen_user.create');
    }

    public function user_store(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:255', 
            'username'  => 'required|string|unique:user,username', 
            'password'  => 'required|min:6',
            'role'      => 'required'
        ]);

        User::create([
            'nama_user' => $request->nama_user, 
            'username'  => $request->username,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
        ]);

        return redirect()->route('direktur.manajemen_user.index')->with('success', 'User authorized successfully!');
    }

    public function user_show($id)
    {
        $user = User::findOrFail($id);
        return view('direktur.manajemen_user.show', compact('user'));
    }

    public function user_edit($id)
    {
        $user = User::findOrFail($id);
        return view('direktur.manajemen_user.edit', compact('user'));
    }

    public function user_update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama_user' => 'required|string|max:255',
            'username'  => 'required|unique:user,username,' . $id . ',id_user', 
            'role'      => 'required'
        ]);

        $user->nama_user = $request->nama_user;
        $user->username = $request->username;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('direktur.manajemen_user.index')->with('success', 'Identity updated!');
    }

    public function user_destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('direktur.manajemen_user.index')->with('success', 'User archived.');
    }

    public function riwayat_donatur() { return view('direktur.dashboard'); }
    public function laporan()         { return view('direktur.dashboard'); }
    public function logistik()        { return view('direktur.dashboard'); }
    public function audit()           { return view('direktur.dashboard'); }
}