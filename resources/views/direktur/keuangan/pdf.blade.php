<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan SEDEKAH</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #334155; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #059669; padding-bottom: 20px; }
        .header h1 { color: #059669; margin: 0; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0; font-size: 12px; color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #059669; color: white; text-transform: uppercase; font-size: 10px; padding: 12px; letter-spacing: 1px; }
        td { padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 11px; text-align: center; }
        .nominal { font-weight: bold; color: #059669; text-align: right; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #94a3b8; }
        .summary { margin-top: 20px; padding: 15px; background: #f8fafc; border-radius: 10px; }
        .summary table { border: none; margin: 0; }
        .summary td { border: none; text-align: left; padding: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Project SEDEKAH</h1>
        <p>Laporan Transaksi Keuangan Yayasan</p>
        <p>Dicetak pada: {{ date('d F Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Order ID</th>
                <th>Tanggal Transaksi</th>
                <th>Status</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($keuangan_list as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->order_id }}</td>
                <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}</td>
                <td style="color: #059669; font-weight: bold;">BERHASIL</td>
                <td class="nominal">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
            </tr>
            @php $total += $item->nominal; @endphp
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <table>
            <tr>
                <td width="80%">TOTAL DANA TERKUMPUL</td>
                <td style="color: #059669; font-size: 16px; text-align: right;">Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Dokumen ini dihasilkan secara otomatis oleh Sistem Manajemen SEDEKAH.</p>
    </div>
</body>
</html>