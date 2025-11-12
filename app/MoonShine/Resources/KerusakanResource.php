<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kerusakan;
use App\Models\InventarisBarang; // Relasi 1
use App\Models\Ruangan; // Relasi 2 (via Inventaris)
use App\Models\Barang; // Relasi 3 (via Inventaris)

use MoonShine\ActionButtons\ActionButton;
use MoonShine\Handlers\ExportHandler;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;

// Import Fields
use MoonShine\Fields\ID;
use MoonShine\Fields\Date;
use MoonShine\Fields\Text;
use MoonShine\Fields\Select;
use MoonShine\Fields\Relationships\BelongsTo;

// Import Filter Fields
use MoonShine\Fields\DateRange;


class KerusakanResource extends ModelResource
{
    protected string $model = Kerusakan::class;
    protected string $title = 'Permintaan Perbaikan';
    public string $titleField = 'no_kerusakan';

    /**
     * Eager load relasi berantai
     */
    public function query(): Builder
    {
        return parent::query()->with([
            'inventarisBarang.ruangan', // Inventaris -> Ruangan
            'inventarisBarang.barang'   // Inventaris -> Barang
        ]);
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No', 'no_kerusakan')->sortable()->showOnExport(),

                Date::make('Tanggal Lapor', 'tanggal')
                    ->format('d-m-Y')
                    ->required()
                    ->sortable()
                    ->showOnExport(),
                
                // Relasi ke Inventaris
                BelongsTo::make(
                    'Barang Inventaris',
                    'inventarisBarang',
                    // Format: "Nama Barang (Kode) di Ruangan X"
                    formatted: fn(InventarisBarang $model) => 
                        ($model->barang->nama_barang ?? 'N/A') . 
                        ' (' . ($model->kode_barang ?? 'N/A') . ') - ' . 
                        ($model->ruangan->nama_ruangan ?? 'N/A')
                )
                    ->searchable() // Penting agar bisa dicari
                    ->required()
                    ->showOnExport(),
                
                Text::make('Kerusakan', 'kerusakan')
                    ->required()
                    ->showOnExport(),

                Select::make('Status', 'status_kerusakan')
                    ->options([
                        'Menunggu Perbaikan' => 'Menunggu Perbaikan',
                        'Sedang Diperbaiki' => 'Sedang Diperbaiki',
                        'Selesai Diperbaiki' => 'Selesai Diperbaiki',
                        'Tidak Dapat Diperbaiki' => 'Tidak Dapat Diperbaiki',
                    ])
                    ->required()
                    ->sortable()
                    ->showOnExport(),
                
                Text::make('Keterangan', 'keterangan_kerusakan')
                    ->nullable()
                    ->hideOnIndex()
                    ->showOnExport(),
            ])
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'tanggal' => ['required', 'date'],
            'no_inventaris' => ['required', 'exists:inventaris_barangs,no_inventaris'],
            'kerusakan' => ['required', 'string', 'max:150'],
            'status_kerusakan' => ['required', 'string', 'max:25'],
            'keterangan_kerusakan' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function getRelations(): array
    {
        return [
            'inventarisBarang',
        ];
    }

    /**
     * Tambahkan Filter
     */
    public function filters(): array
    {
        return [
            // Filter berdasarkan Inventaris (Barang/Kode/Ruangan)
            BelongsTo::make(
                'Barang Inventaris',
                'inventarisBarang',
                formatted: fn(InventarisBarang $model) => ($model->barang->nama_barang ?? 'N/A') . ' (' . ($model->kode_barang ?? 'N/A') . ') - ' . ($model->ruangan->nama_ruangan ?? 'N/A')
            )->searchable(), // Penting agar dropdown bisa dicari
            
            // Filter berdasarkan Status
            Select::make('Status', 'status_kerusakan')
                ->options([
                    'Menunggu Perbaikan' => 'Menunggu Perbaikan',
                    'Sedang Diperbaiki' => 'Sedang Diperbaiki',
                    'Selesai Diperbaiki' => 'Selesai Diperbaiki',
                    'Tidak Dapat Diperbaiki' => 'Tidak Dapat Diperbaiki',
                ])
                ->nullable(), // Boleh tidak diisi

            // Filter berdasarkan Tanggal Lapor
            DateRange::make('Tanggal Lapor', 'tanggal')
                ->format('d-m-Y'),
        ];
    }

    /**
     * Searchable columns
     */
    public function search(): array
    {
        return ['no_kerusakan', 'kerusakan', 'keterangan_kerusakan'];
    }

    /**
     * Matikan Export Bawaan
     */
    public function export(): ?ExportHandler
    {
        return null;
    }

    /**
     * Tambahkan Tombol Aksi Kustom (Reset, PDF, Excel)
     */
    public function actions(): array
    {
        return [
            ActionButton::make('Reset', request()->url())
                ->icon('heroicons.outline.arrow-path')
                ->secondary(),

            ActionButton::make(
                'Cetak PDF', 
                fn() => route('report.kerusakan.pdf') . '?' . request()->getQueryString()
            )
                ->icon('heroicons.outline.printer')
                ->blank(),
            
            ActionButton::make(
                'Export Excel', 
                fn() => route('report.kerusakan.excel') . '?' . request()->getQueryString()
            )
                ->icon('heroicons.outline.table-cells')
                ->blank(),
        ];
    }
}