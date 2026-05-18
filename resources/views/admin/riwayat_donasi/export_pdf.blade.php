<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Riwayat Donasi - Yayasan Rumah Harapan Karawang</title>
    <style>
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            color: #334155; 
            margin: 30px; 
            background: #fff;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 3px double #059669; 
            padding-bottom: 20px; 
        }
        .header h1 { 
            margin: 0; 
            color: #059669; 
            font-size: 22px; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
        }
        .header p { 
            margin: 5px 0 0 0; 
            color: #64748b; 
            font-size: 13px; 
            font-weight: 500;
        }
        .info-cetak {
            font-size: 11px;
            color: #64748b;
            text-align: right;
            margin-bottom: 10px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
            font-size: 12px; 
        }
        th { 
            background-color: #059669; 
            color: white; 
            padding: 10px; 
            text-align: left; 
            text-transform: uppercase; 
            font-size: 11px; 
            letter-spacing: 0.5px; 
            border: 1px solid #059669;
        }
        td { 
            padding: 10px; 
            border: 1px solid #e2e8f0; 
        }
        tr:nth-child(even) { 
            background-color: #f8fafc; 
        }
        .text-center {
            text-align: center;
        }
        .no-print { 
            display: none; 
        }
        @media print {
            .no-print { 
                display: none; 
            }
            body { 
                margin: 0; 
            }
        }
    </style>
</head>
<body>

    {{-- Tombol cetak manual dan script window.print() otomatis telah disesuaikan agar bersih saat diexport langsung menjadi file PDF --}}

    <div class="header">
        <h1>Laporan Riwayat Transaksi Donasi Keseluruhan</h1>
        <p>Yayasan Rumah Harapan Karawang</p>
    </div>

    <div class="info-cetak">
        Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WIB
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 12%;">ID Donasi</th>
                <th>Nama Donatur</th>
                <th class="text-center" style="width: 15%;">Jenis Donasi</th>
                <th class="text-center" style="width: 20%;">Tanggal Donasi</th>
                <th class="text-center" style="width: 15%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $donasi)
            <tr>
                <td class="text-center"><strong>#{{ str_pad($donasi->id_donasi, 4, '0', STR_PAD_LEFT) }}</strong></td>
                <td>{{ $donasi->kunjungan->donatur->nama_donatur ?? 'Hamba Allah' }}</td>
                <td class="text-center">
                    @if($donasi->donasi_uang)
                        Uang
                    @elseif($donasi->donasi_barang && count($donasi->donasi_barang) > 0)
                        Barang
                    @else
                        Lainnya
                    @endif
                </td>
                <td class="text-center">{{ \Carbon\Carbon::parse($donasi->tgl_donasi)->translatedFormat('d F Y') }}</td>
                <td class="text-center">{{ $donasi->status_donasi }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="color: #64748b; font-style: italic;">Tidak ada data riwayat transaksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>