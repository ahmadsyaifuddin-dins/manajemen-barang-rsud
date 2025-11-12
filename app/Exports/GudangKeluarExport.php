<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class GudangKeluarExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $keluarData;
    protected $rowNumber = 0;

    public function __construct(Collection $keluarData)
    {
        $this->keluarData = $keluarData;
    }

    public function collection()
    {
        return $this->keluarData;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Barang',
            'Kategori',
            'Tanggal Keluar',
            'Jumlah Keluar',
            'Ruangan Penerima',
            'Keterangan',
        ];
    }

    public function map($item): array
    {
        $this->rowNumber++;
        
        // Akses relasi berantai: GudangKeluar -> GudangStok -> BarangGudang
        $namaBarang = $item->gudangStok->barangGudang->nama_barang_gudang ?? 'N/A';
        $kategoriBarang = $item->gudangStok->barangGudang->kategori_barang_gudang ?? 'N/A';
        // Akses relasi langsung: GudangKeluar -> Ruangan
        $namaRuangan = $item->ruangan->nama_ruangan ?? 'N/A';

        return [
            $this->rowNumber,
            $namaBarang,
            $kategoriBarang,
            $item->tanggal_keluar ? Carbon::parse($item->tanggal_keluar)->format('d-m-Y') : '',
            $item->jumlah_keluar,
            $namaRuangan,
            $item->keterangan_keluar,
        ];
    }
}