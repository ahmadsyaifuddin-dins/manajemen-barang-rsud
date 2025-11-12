<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class RusakExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
            'Tgl Dicatat Rusak',
            'Barang (Kode)',
            'Ruangan',
            'Laporan Kerusakan',
            'Status Akhir Perbaikan',
            'Keterangan Rusak',
        ];
    }

    public function map($item): array
    {
        $this->rowNumber++;
        
        // Relasi: Rusak -> Perbaikan -> Kerusakan -> InventarisBarang -> (Ruangan & Barang)
        $perbaikan = $item->perbaikan;
        $kerusakan = $perbaikan->kerusakan ?? null;
        $inventaris = $kerusakan->inventarisBarang ?? null;
        
        $namaBarang = $inventaris->barang->nama_barang ?? 'N/A';
        $kodeBarang = $inventaris->kode_barang ?? 'N/A';
        $ruangan = $inventaris->ruangan->nama_ruangan ?? 'N/A';
        $laporanKerusakan = $kerusakan->kerusakan ?? 'N/A';
        $statusPerbaikan = $perbaikan->status ?? 'N/A'; // (Seharusnya 'Tidak Dapat Diperbaiki')

        return [
            $this->rowNumber,
            $item->tanggal_rusak ? Carbon::parse($item->tanggal_rusak)->format('d-m-Y') : '',
            $namaBarang . ' (' . $kodeBarang . ')',
            $ruangan,
            $laporanKerusakan,
            $statusPerbaikan,
            $item->keterangan_rusak,
        ];
    }
}