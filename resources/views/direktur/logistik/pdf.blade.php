<!DOCTYPE html>
<html>
<head>
    <title>Laporan Logistik - Project SEDEKAH</title>
    <style>
        /* Desain Modern & Clean untuk Project SEDEKAH */
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #1a5928; padding-bottom: 10px; }
        .header h2 { color: #1a5928; margin: 0; text-transform: uppercase; letter-spacing: 1px; }
        .header p { font-size: 13px; color: #555; margin: 5px 0; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #1a5928; color: white; font-size: 11px; text-transform: uppercase; padding: 12px; border: 1px solid #ddd; }
        td { padding: 10px; border: 1px solid #ddd; font-size: 11px; text-align: center; }
        
        .footer { margin-top: 40px; text-align: right; font-size: 11px; color: #444; }
        .stok-kritis { color: #d9534f; font-weight: bold; }
        .stok-aman { color: #5cb85c; font-weight: bold; }
        
        .signature { margin-top: 60px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Inventaris Donasi Barang</h2>
        <p>Yayasan Project SEDEKAH</p>
        <p>Tanggal Laporan: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Barang</th>
                <th width="20%">Kategori</th>
                <th width="10%">Jumlah</th>
                <th width="10%">Satuan</th>
                <th width="15%">Status Stok</th>
                <th width="10%">Tgl Masuk</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logistik_list as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td style="text-align: left; font-weight: bold;">{{ $item->nama_barang }}</td>
                <td>{{ $item->kategori_barang->nama_kategori ?? 'Umum' }}</td>
                <td>{{ $item->jumlah_barang }}</td>
                <td>{{ $item->satuan_barang }}</td>
                <td>
                    <span class="{{ $item->jumlah_barang <= 5 ? 'stok-kritis' : 'stok-aman' }}">
                        {{ $item->jumlah_barang <= 5 ? 'STOK RENDAH' : 'TERSEDIA' }}
                    </span>
                </td>
                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7">Tidak ada data logistik yang tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        <div class="signature">
            <p>Mengetahui,</p>
            <br><br><br>
            <p>Direktur Yayasan Rumah Harapan</p>
        </div>
    </div>
</body>
</html>