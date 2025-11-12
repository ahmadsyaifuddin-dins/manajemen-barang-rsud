<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Perbaikan;
use App\Models\Kerusakan; // Relasi 1
use App\Models\InventarisBarang; // Relasi 2 (via Kerusakan)
use Carbon\Carbon;
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


class PerbaikanResource extends ModelResource
{
    protected string $model = Perbaikan::class;
    protected string $title = 'Perbaikan Barang';
    public string $titleField = 'no_perbaikan';

    /**
     * Eager load relasi berantai
     */
    public function query(): Builder
    {
        // Ini adalah relasi berantai yang kompleks:
        // Perbaikan -> Kerusakan -> InventarisBarang -> (Ruangan & Barang)
        return parent::query()->with([
            'kerusakan.inventarisBarang.ruangan',
            'kerusakan.inventarisBarang.barang'
        ]);
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No', 'no_perbaikan')->sortable()->showOnExport(),

                // Relasi ke Kerusakan (Laporan Awal)
                BelongsTo::make(
                    'Laporan Kerusakan',
                    'kerusakan',
                    // Format: "Barang X (Rusak: ABC) - Tgl Lapor: Y"
                    formatted: fn(Kerusakan $model) => 
                        ($model->inventarisBarang->barang->nama_barang ?? 'N/A') .
                        ' (Kerusakan: ' . $model->kerusakan . ') - ' .
                        Carbon::parse($model->tanggal)->format('d-m-Y')
                )
                    ->searchable() // Penting agar bisa dicari
                    ->required()
                    ->showOnExport(),
                
                Date::make('Tanggal Perbaikan', 'tanggal_perbaikan')
                    ->format('d-m-Y')
                    ->required()
                    ->sortable()
                    ->showOnExport(),

                Select::make('Status', 'status')
                    ->options([
                        'Sedang Diperbaiki' => 'Sedang Diperbaiki',
                        'Selesai Diperbaiki' => 'Selesai Diperbaiki',
                        'Tidak Dapat Diperbaiki' => 'Tidak Dapat Diperbaiki',
                    ])
                    ->required()
                    ->sortable()
                    ->showOnExport(),
                
                Select::make('Kondisi Selesai', 'kondisi_perbaikan')
                    ->options([
                        'Baik' => 'Baik',
                        'Rusak' => 'Rusak',
                        // Opsi 'Belum' mungkin tidak relevan di sini
                    ])
                    ->required()
                    ->sortable()
                    ->showOnExport(),

                Text::make('Keterangan', 'keterangan')
                    ->nullable()
                    ->hideOnIndex()
                    ->showOnExport(),
            ])
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'no_kerusakan' => ['required', 'exists:kerusakans,no_kerusakan'],
            'tanggal_perbaikan' => ['required', 'date'],
            'status' => ['required', 'string', 'max:25'],
            'kondisi_perbaikan' => ['required', 'string', 'max:5'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function getRelations(): array
    {
        return [
            'kerusakan',
        ];
    }

    /**
     * Tambahkan Filter
     */
    public function filters(): array
    {
        return [
            // Filter berdasarkan Laporan Kerusakan
            BelongsTo::make(
                'Laporan Kerusakan',
                'kerusakan',
                formatted: fn(Kerusakan $model) => ($model->inventarisBarang->barang->nama_barang ?? 'N/A') . ' (' . $model->kerusakan . ')'
            )->searchable(),
            
            // Filter berdasarkan Status Perbaikan
            Select::make('Status', 'status')
                ->options([
                    'Sedang Diperbaiki' => 'Sedang Diperbaiki',
                    'Selesai Diperbaiki' => 'Selesai Diperbaiki',
                    'Tidak Dapat Diperbaiki' => 'Tidak Dapat Diperbaiki',
                ])
                ->nullable(),
                
            // Filter berdasarkan Kondisi Selesai
            Select::make('Kondisi Selesai', 'kondisi_perbaikan')
                ->options(['Baik' => 'Baik', 'Rusak' => 'Rusak'])
                ->nullable(),

            // Filter berdasarkan Tanggal Perbaikan
            DateRange::make('Tanggal Perbaikan', 'tanggal_perbaikan')
                ->format('d-m-Y'),
        ];
    }

    /**
     * Searchable columns
     */
    public function search(): array
    {
        return ['no_perbaikan', 'keterangan'];
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
                fn() => route('report.perbaikan.pdf') . '?' . request()->getQueryString()
            )
                ->icon('heroicons.outline.printer')
                ->blank(),
            
            ActionButton::make(
                'Export Excel', 
                fn() => route('report.perbaikan.excel') . '?' . request()->getQueryString()
            )
                ->icon('heroicons.outline.table-cells')
                ->blank(),
        ];
    }
}