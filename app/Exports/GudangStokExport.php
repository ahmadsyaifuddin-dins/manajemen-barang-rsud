<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GudangStokExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $stokData;
    protected $rowNumber = 0; // Untuk nomor urut

    /**
     * Terima koleksi data yang sudah terfilter dari Controller
     */
    public function __construct(Collection $stokData)
    {
        $this->stokData = $stokData;
    }

    /**
     * Kembalikan data yang akan diekspor
     */
    public function collection()
    {
        return $this->stokData;
    }

    /**
     * Tentukan judul kolom (header)
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Barang',
            'Jenis Barang',
            'Kategori',
            'Jumlah Stok',
            'Keterangan',
        ];
    }

    /**
     * Petakan data dari tiap baris
     * $item adalah 1 baris dari $stokData
     */
    public function map($item): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,
            $item->barangGudang->nama_barang_gudang ?? 'N/A',
            $item->barangGudang->jenis_barang_gudang ?? 'N/A',
            $item->barangGudang->kategori_barang_gudang ?? 'N/A',
            $item->jumlah_barang,
            $item->keterangan_gudang,
        ];
    }
}