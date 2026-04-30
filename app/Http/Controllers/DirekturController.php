<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirekturController extends Controller
{
    public function index()
    {
        // Data Statis untuk Dashboard Direktur (High-Level Monitoring)
        $data = [
            'totalAsetYayasan'    => 1250000000, // Rp 1.25 Miliar
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

    public function riwayat_donatur()
    {
        return view('direktur.dashboard'); // Sementara arahkan ke dashboard atau buat view baru nanti
    }

    public function laporan()
    {
        return view('direktur.dashboard'); 
    }

    public function logistik()
    {
        return view('direktur.dashboard');
    }

    public function audit()
    {
        return view('direktur.dashboard');
    }
}