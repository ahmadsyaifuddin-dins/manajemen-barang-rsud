<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Rusak;
use App\Models\Perbaikan; // Import model relasi
use App\Models\Kerusakan; // Untuk detail
use App\Models\InventarisBarang; // Untuk detail
use App\Models\Barang; // Untuk detail
use App\Models\Ruangan; // Untuk detail

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Date;
use MoonShine\Fields\Textarea;
use MoonShine\Fields\Relationships\BelongsTo;

class RusakResource extends ModelResource
{
    // Properti TANPA static
    protected string $model = Rusak::class;

    protected string $title = 'Barang Rusak Berat'; // Judul

    // Kolom yang tampil di relasi atau pencarian default
    public string $titleField = 'no_rusak';

    // Urutan menu (setelah Serah Terima)
    protected int $priority = 9;

    // Field utama
    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No', 'no_rusak')->sortable(),

                // Relasi ke Perbaikan (Menampilkan detail barang, ruangan, status perbaikan)
                BelongsTo::make('Detail Perbaikan', 'perbaikan',
                    // Closure untuk format tampilan
                    function(Perbaikan $perbaikan) {
                        $kerusakan = $perbaikan->kerusakan;
                        $inventaris = $kerusakan?->inventarisBarang;
                        $barang = $inventaris?->barang ? ($inventaris->barang->jenis_barang . ' ' . $inventaris->barang->nama_barang) : 'N/A';
                        $ruangan = $inventaris?->ruangan->nama_ruangan ?? 'N/A';
                        $statusPerbaikan = $perbaikan->status ?? 'N/A';
                        $kondisi = $perbaikan->kondisi_perbaikan ?? 'N/A';
                        // Tampilkan juga detail kerusakan awal
                        $detailKerusakan = $kerusakan->kerusakan ?? 'N/A';
                        return "{$barang} ({$ruangan}) - Rusak Awal: {$detailKerusakan} - Status Akhir: {$statusPerbaikan} (Kondisi: {$kondisi})";
                    }
                )
                    ->searchable()
                    ->required(),

                Date::make('Tanggal Dinyatakan Rusak', 'tanggal_rusak')
                    ->format('d-m-Y')
                    ->required()
                    ->sortable(),

                Textarea::make('Keterangan Rusak Berat', 'keterangan_rusak')
                    ->nullable()
                    ->hideOnIndex(),
            ])
        ];
    }

    // Aturan validasi
    public function rules(Model $item): array
    {
        return [
            // Pastikan hanya perbaikan yg statusnya 'Tidak bisa diperbaiki' yg bisa dipilih? (Perlu custom rule/filter)
            'no_perbaikan' => ['required', 'exists:perbaikans,no_perbaikan'],
            'tanggal_rusak' => ['required', 'date'],
            'keterangan_rusak' => ['nullable', 'string', 'max:100'],
        ];
    }

    // Searchable columns
    public function search(): array
    {
        return ['no_rusak', 'tanggal_rusak', 'keterangan_rusak'];
    }

    // Definisikan relasi agar BelongsTo berfungsi
    public function getRelations(): array
    {
        return [
             'perbaikan' => [ // Relasi utama
                'kerusakan' => [ // Relasi di dalam perbaikan
                    'inventarisBarang' => [ // Relasi di dalam kerusakan
                        'barang', // Relasi di dalam inventarisBarang
                        'ruangan' // Relasi di dalam inventarisBarang
                    ]
                ],
                'no_perbaikan', // PK perbaikan
                'status',       // Kolom status perbaikan
                'kondisi_perbaikan' // Kolom kondisi
            ],
        ];
    }

    // filters() and actions() can be added here if needed
    // Mungkin perlu filter untuk hanya menampilkan Perbaikan yg statusnya 'Tidak bisa diperbaiki' saat create
}