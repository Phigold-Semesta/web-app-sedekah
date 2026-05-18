<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class DonasiKeseluruhanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $query;

    // Menerima query yang sudah difilter dari Controller
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * Ambil query data untuk dieksport
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * Judul Kolom di file Excel / CSV / PDF
     */
    public function headings(): array
    {
        return [
            'ID DONASI',
            'NAMA DONATUR',
            'JENIS DONASI',
            'TANGGAL DONASI',
            'STATUS DONASI'
        ];
    }

    /**
     * Mapping / Format data tiap baris sebelum dimasukkan ke file
     */
    public function map($donasi): array
    {
        return [
            '#' . str_pad($donasi->id_donasi, 4, '0', STR_PAD_LEFT),
            $donasi->kunjungan->donatur->nama_donatur ?? 'Hamba Allah',
            $donasi->donasi_uang ? 'Uang' : 'Barang',
            Carbon::parse($donasi->tgl_donasi)->translatedFormat('d F Y'),
            strtoupper($donasi->status_donasi)
        ];
    }

    /**
     * Styling Mewah Berorientasi Ekspor: Emerald Header & Border HANYA sampai kolom E
     */
    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $rangeFull = 'A1:E' . $highestRow; // Dibatasi ketat hanya sampai kolom E (Status Donasi)

        // 1. Set alignment tengah untuk seluruh kolom yang digunakan
        $sheet->getStyle($rangeFull)
              ->getAlignment()
              ->setVertical(Alignment::VERTICAL_CENTER)
              ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // 2. Styling Header Mewah (Hanya baris pertama A1 sampai E1)
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'bold' => true, 
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '059669'] // Emerald-600 khas SOWAN
            ],
        ]);

        // 3. Tambahkan border tipis estetik pada seluruh area data aktif
        $sheet->getStyle($rangeFull)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'], // Garis abu-abu clean modern
                ],
            ],
        ]);

        return [
            // Kolom C (Jenis Donasi: Uang / Barang) dibuat Bold Emerald agar stand out saat dicetak
            'C' => [
                'font' => [
                    'bold' => true, 
                    'color' => ['rgb' => '059669']
                ]
            ],
            // Pastikan baris header teratas tetap terkunci Bold Putih (meng-override style kolom C)
            1 => [
                'font' => [
                    'bold' => true, 
                    'color' => ['rgb' => 'FFFFFF']
                ]
            ],
        ];
    }
}