<?php

namespace App\Exports;

use App\Models\DonasiBarang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DonasiBarangExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = DonasiBarang::with('kategori_barang');

        if (!empty($this->filters['search'])) {
            $query->where('nama_barang', 'like', '%' . $this->filters['search'] . '%');
        }

        // Menambahkan filter tanggal jika ada di request
        if (!empty($this->filters['tgl_hari'])) {
            $query->whereDate('created_at', $this->filters['tgl_hari']);
        }

        return $query->latest()->get();
    }

    /**
    * Menentukan Judul Header
    */
    public function headings(): array
    {
        return [
            'ID ITEM',
            'NAMA BARANG',
            'KATEGORI',
            'TANGGAL MASUK',
            'JUMLAH BARANG',
            'SATUAN',
            'STATUS',
        ];
    }

    /**
    * Memetakan data sesuai urutan kolom
    * Bagian Status diubah menjadi "STOK TERSEDIA"
    */
    public function map($item): array
    {
        return [
            $item->id_donasi_barang ?? $item->id,
            strtoupper($item->nama_barang),
            $item->kategori_barang->nama_kategori ?? 'Umum',
            \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y'),
            $item->jumlah_barang,
            strtoupper($item->satuan_barang),
            'STOK TERSEDIA', // Perubahan: Mengganti logika Stok Kritis menjadi Stok Tersedia
        ];
    }

    /**
    * Styling Mewah: Emerald Header & Border HANYA sampai kolom G
    */
    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $rangeFull = 'A1:G' . $highestRow; // Dibatasi hanya sampai kolom G (Status)

        // 1. Set alignment tengah untuk kolom yang digunakan saja
        $sheet->getStyle($rangeFull)
              ->getAlignment()
              ->setVertical(Alignment::VERTICAL_CENTER)
              ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // 2. Styling Header (Hanya A1 sampai G1)
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true, 
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '059669'] // Emerald-600
            ],
        ]);

        // 3. Tambahkan border hanya pada area data (A sampai G)
        $sheet->getStyle($rangeFull)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ]);

        return [
            // Kolom E (Jumlah Barang) dibuat Bold Emerald agar stand out
            'E' => [
                'font' => [
                    'bold' => true, 
                    'color' => ['rgb' => '059669']
                ]
            ],
            // Pastikan baris header tetap Bold Putih (meng-override style kolom E)
            1 => [
                'font' => [
                    'bold' => true, 
                    'color' => ['rgb' => 'FFFFFF']
                ]
            ],
        ];
    }
}