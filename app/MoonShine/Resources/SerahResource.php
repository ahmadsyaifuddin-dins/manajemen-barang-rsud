<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Serah;
use App\Models\Perbaikan; // Relasi 1
use App\Models\Kerusakan; // Relasi 2 (via Perbaikan)
use App\Models\InventarisBarang; // Relasi 3 (via Kerusakan)
use Carbon\Carbon;
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


class SerahResource extends ModelResource
{
    protected string $model = Serah::class;
    protected string $title = 'Serah Terima Barang';
    public string $titleField = 'no_serah';

    /**
     * Eager load relasi berantai paling panjang
     */
    public function query(): Builder
    {
        // Serah -> Perbaikan -> Kerusakan -> InventarisBarang -> (Ruangan & Barang)
        return parent::query()->with([
            'perbaikan.kerusakan.inventarisBarang.ruangan',
            'perbaikan.kerusakan.inventarisBarang.barang'
        ]);
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No', 'no_serah')->sortable()->showOnExport(),

                // Relasi ke Perbaikan
                BelongsTo::make(
                    'Item Perbaikan',
                    'perbaikan',
                    // Format: "Barang X (Status: Selesai) - Tgl Perbaikan: Y"
                    formatted: fn(Perbaikan $model) => 
                        ($model->kerusakan->inventarisBarang->barang->nama_barang ?? 'N/A') .
                        ' (Status: ' . $model->status . ') - ' .
                        Carbon::parse($model->tanggal_perbaikan)->format('d-m-Y')
                )
                    ->searchable()
                    ->required()
                    ->showOnExport(),
                
                Date::make('Tanggal Serah', 'tanggal_serah')
                    ->format('d-m-Y')
                    ->required()
                    ->sortable()
                    ->showOnExport(),

                Text::make('Keterangan Serah', 'keterangan_serah')
                    ->nullable()
                    ->hideOnIndex()
                    ->showOnExport(),
            ])
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'no_perbaikan' => ['required', 'exists:perbaikans,no_perbaikan', 'unique:serahs,no_perbaikan,' . $item->getKey()], // Pastikan 1 perbaikan hanya 1 serah terima
            'tanggal_serah' => ['required', 'date'],
            'keterangan_serah' => ['nullable', 'string'],
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
            
            // Filter berdasarkan Tanggal Serah
            DateRange::make('Tanggal Serah', 'tanggal_serah')
                ->format('d-m-Y'),
        ];
    }

    /**
     * Searchable columns
     */
    public function search(): array
    {
        return ['no_serah', 'keterangan_serah'];
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
                fn() => route('report.serah.pdf') . '?' . request()->getQueryString()
            )
                ->icon('heroicons.outline.printer')
                ->blank(),
            
            ActionButton::make(
                'Export Excel', 
                fn() => route('report.serah.excel') . '?' . request()->getQueryString()
            )
                ->icon('heroicons.outline.table-cells')
                ->blank(),
        ];
    }
}