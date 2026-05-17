<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class DonasiKeseluruhanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
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
     * Judul Kolom di file Excel / CSV
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
}