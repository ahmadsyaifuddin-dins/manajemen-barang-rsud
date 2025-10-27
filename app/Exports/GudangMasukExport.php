<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class GudangMasukExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $masukData;
    protected $rowNumber = 0;

    public function __construct(Collection $masukData)
    {
        $this->masukData = $masukData;
    }

    public function collection()
    {
        return $this->masukData;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Barang',
            'Jenis Barang',
            'Kategori',
            'Tanggal Masuk',
            'Jumlah Masuk',
        ];
    }

    public function map($item): array
    {
        $this->rowNumber++;
        
        // Akses relasi berantai: GudangMasuk -> GudangStok -> BarangGudang
        $namaBarang = $item->gudangStok->barangGudang->nama_barang_gudang ?? 'N/A';
        $jenisBarang = $item->gudangStok->barangGudang->jenis_barang_gudang ?? 'N/A';
        $kategoriBarang = $item->gudangStok->barangGudang->kategori_barang_gudang ?? 'N/A';

        return [
            $this->rowNumber,
            $namaBarang,
            $jenisBarang,
            $kategoriBarang,
            $item->tanggal_masuk ? Carbon::parse($item->tanggal_masuk)->format('d-m-Y') : '',
            $item->jumlah_masuk,
        ];
    }
}