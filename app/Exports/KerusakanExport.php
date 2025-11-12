<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class KerusakanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
            'Tanggal Lapor',
            'Ruangan',
            'Nama Barang',
            'Kode Barang',
            'Kerusakan',
            'Status',
            'Keterangan',
        ];
    }

    public function map($item): array
    {
        $this->rowNumber++;
        
        // Akses relasi berantai: Kerusakan -> InventarisBarang -> Ruangan/Barang
        $ruangan = $item->inventarisBarang->ruangan->nama_ruangan ?? 'N/A';
        $namaBarang = $item->inventarisBarang->barang->nama_barang ?? 'N/A';
        $kodeBarang = $item->inventarisBarang->kode_barang ?? 'N/A';

        return [
            $this->rowNumber,
            $item->tanggal ? Carbon::parse($item->tanggal)->format('d-m-Y') : '',
            $ruangan,
            $namaBarang,
            $kodeBarang,
            $item->kerusakan,
            $item->status_kerusakan,
            $item->keterangan_kerusakan,
        ];
    }
}