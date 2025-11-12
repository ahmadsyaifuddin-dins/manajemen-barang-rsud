<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class SerahExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
            'Tgl Serah',
            'Barang (Kode)',
            'Ruangan',
            'Status Perbaikan',
            'Kondisi Diserahkan',
            'Keterangan Serah',
        ];
    }

    public function map($item): array
    {
        $this->rowNumber++;
        
        // Akses relasi berantai: Serah -> Perbaikan -> Kerusakan -> InventarisBarang -> (Ruangan & Barang)
        $perbaikan = $item->perbaikan;
        $kerusakan = $perbaikan->kerusakan ?? null;
        $inventaris = $kerusakan->inventarisBarang ?? null;
        
        $namaBarang = $inventaris->barang->nama_barang ?? 'N/A';
        $kodeBarang = $inventaris->kode_barang ?? 'N/A';
        $ruangan = $inventaris->ruangan->nama_ruangan ?? 'N/A';
        $statusPerbaikan = $perbaikan->status ?? 'N/A';
        $kondisiPerbaikan = $perbaikan->kondisi_perbaikan ?? 'N/A';


        return [
            $this->rowNumber,
            $item->tanggal_serah ? Carbon::parse($item->tanggal_serah)->format('d-m-Y') : '',
            $namaBarang . ' (' . $kodeBarang . ')',
            $ruangan,
            $statusPerbaikan,
            $kondisiPerbaikan,
            $item->keterangan_serah,
        ];
    }
}