<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rusak;
use App\Models\Perbaikan; // Relasi 1
use App\Models\Kerusakan; // Relasi 2 (via Perbaikan)
use App\Models\InventarisBarang; // Relasi 3 (via Kerusakan)

use MoonShine\ActionButtons\ActionButton;
use MoonShine\Handlers\ExportHandler;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;

// Import Fields
use MoonShine\Fields\ID;
use MoonShine\Fields\Date;
use MoonShine\Fields\Text;
use MoonShine\Fields\Relationships\BelongsTo;

// Import Filter Fields
use MoonShine\Fields\DateRange;


class RusakResource extends ModelResource
{
    protected string $model = Rusak::class;
    protected string $title = 'Barang Rusak Berat';
    public string $titleField = 'no_rusak';

    /**
     * Eager load relasi berantai paling panjang
     */
    public function query(): Builder
    {
        // Rusak -> Perbaikan -> Kerusakan -> InventarisBarang -> (Ruangan & Barang)
        return parent::query()->with([
            'perbaikan.kerusakan.inventarisBarang.ruangan',
            'perbaikan.kerusakan.inventarisBarang.barang'
        ]);
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No', 'no_rusak')->sortable()->showOnExport(),

                // Relasi ke Perbaikan
                BelongsTo::make(
                    'Item Perbaikan',
                    'perbaikan',
                    // Format: "Barang X (Status: Tidak Dapat Diperbaiki)"
                    formatted: fn(Perbaikan $model) => 
                        ($model->kerusakan->inventarisBarang->barang->nama_barang ?? 'N/A') .
                        ' (Status: ' . $model->status . ')'
                )
                    ->searchable()
                    ->required()
                    ->showOnExport(),
                
                Date::make('Tanggal Dicatat Rusak', 'tanggal_rusak')
                    ->format('d-m-Y')
                    ->required()
                    ->sortable()
                    ->showOnExport(),

                Text::make('Keterangan Rusak', 'keterangan_rusak')
                    ->nullable()
                    ->hideOnIndex()
                    ->showOnExport(),
            ])
        ];
    }

    public function rules(Model $item): array
    {
        return [
            // Pastikan 1 perbaikan hanya 1x dicatat rusak
            'no_perbaikan' => ['required', 'exists:perbaikans,no_perbaikan', 'unique:rusaks,no_perbaikan,' . $item->getKey()], 
            'tanggal_rusak' => ['required', 'date'],
            'keterangan_rusak' => ['nullable', 'string'],
        ];
    }

    public function getRelations(): array
    {
        return [
            'perbaikan',
        ];
    }

    /**
     * Tambahkan Filter
     */
    public function filters(): array
    {
        return [
            // Filter berdasarkan Item Perbaikan
            BelongsTo::make(
                'Item Perbaikan',
                'perbaikan',
                // Format: "Barang X (Status: Selesai)"
                formatted: fn(Perbaikan $model) => 
                    ($model->kerusakan->inventarisBarang->barang->nama_barang ?? 'N/A') .
                    ' (Status: ' . $model->status . ')'
            )->searchable(),
            
            // Filter berdasarkan Tanggal Rusak
            DateRange::make('Tanggal Rusak', 'tanggal_rusak')
                ->format('d-m-Y'),
        ];
    }

    /**
     * Searchable columns
     */
    public function search(): array
    {
        return ['no_rusak', 'keterangan_rusak'];
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
                fn() => route('report.rusak.pdf') . '?' . request()->getQueryString()
            )
                ->icon('heroicons.outline.printer')
                ->blank(),
            
            ActionButton::make(
                'Export Excel', 
                fn() => route('report.rusak.excel') . '?' . request()->getQueryString()
            )
                ->icon('heroicons.outline.table-cells')
                ->blank(),
        ];
    }
}