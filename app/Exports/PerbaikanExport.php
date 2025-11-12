<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class PerbaikanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $data;
    protected $rowNumber = 0;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tgl Perbaikan',
            'Barang (Kode)',
            'Ruangan',
            'Laporan Kerusakan',
            'Status Perbaikan',
            'Kondisi Selesai',
            'Keterangan',
        ];
    }

    public function map($item): array
    {
        $this->rowNumber++;
        
        // Akses relasi berantai: Perbaikan -> Kerusakan -> InventarisBarang -> (Ruangan & Barang)
        $kerusakan = $item->kerusakan;
        $inventaris = $kerusakan->inventarisBarang ?? null;
        
        $namaBarang = $inventaris->barang->nama_barang ?? 'N/A';
        $kodeBarang = $inventaris->kode_barang ?? 'N/A';
        $ruangan = $inventaris->ruangan->nama_ruangan ?? 'N/A';
        $laporanKerusakan = $kerusakan->kerusakan ?? 'N/A';

        return [
            $this->rowNumber,
            $item->tanggal_perbaikan ? Carbon::parse($item->tanggal_perbaikan)->format('d-m-Y') : '',
            $namaBarang . ' (' . $kodeBarang . ')',
            $ruangan,
            $laporanKerusakan,
            $item->status,
            $item->kondisi_perbaikan,
            $item->keterangan,
        ];
    }
}