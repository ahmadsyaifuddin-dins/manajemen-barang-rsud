<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Serah;
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

class SerahResource extends ModelResource
{
    // Properti TANPA static
    protected string $model = Serah::class;

    protected string $title = 'Serah Terima Barang'; // Judul

    // Kolom yang tampil di relasi atau pencarian default
    public string $titleField = 'no_serah';

    // Urutan menu (setelah Perbaikan Barang)
    protected int $priority = 8;

    // Field utama
    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No', 'no_serah')->sortable(),

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
                        return "{$barang} ({$ruangan}) - Status: {$statusPerbaikan} (Kondisi: {$kondisi})";
                    }
                )
                    ->searchable() // Memungkinkan pencarian
                    ->required(),

                Date::make('Tanggal Serah', 'tanggal_serah')
                    ->format('d-m-Y')
                    ->required()
                    ->sortable(),

                Textarea::make('Keterangan Serah Terima', 'keterangan_serah')
                    ->nullable()
                    ->hideOnIndex(),
            ])
        ];
    }

    // Aturan validasi
    public function rules(Model $item): array
    {
        return [
            'no_perbaikan' => ['required', 'exists:perbaikans,no_perbaikan'],
            'tanggal_serah' => ['required', 'date'],
            'keterangan_serah' => ['nullable', 'string', 'max:100'],
        ];
    }

    // Searchable columns
    public function search(): array
    {
        return ['no_serah', 'tanggal_serah', 'keterangan_serah'];
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
}