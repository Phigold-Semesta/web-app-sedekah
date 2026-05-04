<?php

namespace App\Exports;

use App\Models\DonasiUang;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DonasiUangExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    /**
    * Query data berdasarkan filter yang dikirim dari Controller
    */
    public function query()
    {
        // Berdasarkan struktur tabel di phpMyAdmin
        $query = DonasiUang::query()->orderBy('created_at', 'desc');

        if (!empty($this->filters['search'])) {
            $query->where('order_id', 'like', '%' . $this->filters['search'] . '%');
        }

        if (!empty($this->filters['tgl_hari'])) {
            $query->whereDate('created_at', $this->filters['tgl_hari']);
        }

        return $query;
    }

    /**
    * Header kolom di Excel
    */
    public function headings(): array
    {
        return [
            'ID Donasi',
            'Order ID',
            'Nominal (Rp)',
            'Snap Token',
            'Tanggal Transaksi',
            'Status'
        ];
    }

    /**
    * Mapping data agar rapi saat masuk ke baris Excel
    */
    public function map($donasi): array
    {
        return [
            $donasi->id_donasi_uang,
            $donasi->order_id,
            number_format($donasi->nominal, 0, ',', '.'),
            $donasi->snap_token ?? '-',
            $donasi->created_at->format('d-m-Y H:i'),
            'Terbayar' // Status default berdasarkan logika dashboard direktur
        ];
    }

    /**
    * Styling sederhana agar header terlihat profesional
    */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '059669'] // Warna Emerald Green
                ]
            ],
        ];
    }
}