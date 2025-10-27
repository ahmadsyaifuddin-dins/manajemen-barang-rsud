<?php

namespace App\Exports;

use Illuminate\Support\Collection; // Penting!
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class InventarisExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $inventarisData;
    protected $rowNumber = 0; // Untuk nomor urut

    /**
     * Terima koleksi data yang sudah terfilter
     */
    public function __construct(Collection $inventarisData)
    {
        $this->inventarisData = $inventarisData;
    }

    /**
     * Kembalikan data yang akan diekspor
     */
    public function collection()
    {
        return $this->inventarisData;
    }

    /**
     * Tentukan judul kolom (header)
     */
    public function headings(): array
    {
        return [
            'No',
            'Ruangan',
            'Nama Barang',
            'Jenis Barang',
            'Kode Barang',
            'Tanggal Masuk',
            'Kondisi',
            'Keterangan',
        ];
    }

    /**
     * Petakan data dari tiap baris
     * $item adalah 1 baris dari $inventarisData
     */
    public function map($item): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,
            $item->ruangan->nama_ruangan ?? 'N/A',
            $item->barang->nama_barang ?? 'N/A',
            $item->barang->jenis_barang ?? 'N/A',
            $item->kode_barang,
            $item->tanggal_masuk ? Carbon::parse($item->tanggal_masuk)->format('d-m-Y') : '',
            $item->kondisi,
            $item->keterangan_inventaris,
        ];
    }
}