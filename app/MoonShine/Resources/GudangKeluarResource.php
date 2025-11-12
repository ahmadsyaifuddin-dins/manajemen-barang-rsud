<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\GudangKeluar;
use App\Models\GudangStok; // Relasi 1
use App\Models\Ruangan;   // Relasi 2

use MoonShine\ActionButtons\ActionButton;
use MoonShine\Handlers\ExportHandler;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;

// Import Fields
use MoonShine\Fields\ID;
use MoonShine\Fields\Date;
use MoonShine\Fields\Number;
use MoonShine\Fields\Text;
use MoonShine\Fields\Relationships\BelongsTo;

// Import Filter Fields
use MoonShine\Fields\DateRange;


class GudangKeluarResource extends ModelResource
{
    protected string $model = GudangKeluar::class;
    protected string $title = 'Barang Keluar';
    public string $titleField = 'no_gudang_keluar';
    protected int $priority = 3; // Di bawah Barang Masuk

    /**
     * SANGAT PENTING: Eager load SEMUA relasi
     */
    public function query(): Builder
    {
        return parent::query()->with([
            'gudangStok.barangGudang', // Relasi berantai ke barang
            'ruangan'                  // Relasi ke ruangan
        ]);
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No', 'no_gudang_keluar')->sortable()->showOnExport(),

                // Relasi ke Barang
                BelongsTo::make(
                    'Barang',
                    'gudangStok',
                    formatted: fn(GudangStok $model) => $model->barangGudang->nama_barang_gudang
                )
                    ->searchable()
                    ->required()
                    ->showOnExport(),
                
                // Relasi ke Ruangan
                BelongsTo::make(
                    'Ruangan Penerima',
                    'ruangan', // Nama method relasi di model GudangKeluar
                    formatted: fn(Ruangan $model) => $model->nama_ruangan
                )
                    ->searchable()
                    ->required()
                    ->showOnExport(),

                Date::make('Tanggal Keluar', 'tanggal_keluar')
                    ->format('d-m-Y')
                    ->required()
                    ->sortable()
                    ->showOnExport(),

                Number::make('Jumlah Keluar', 'jumlah_keluar')
                    ->required()
                    ->sortable()
                    ->showOnExport(),
                
                Text::make('Keterangan', 'keterangan_keluar')
                    ->nullable()
                    ->hideOnIndex() // Sembunyikan di index
                    ->showOnExport(),
            ])
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'no_gudang' => ['required', 'exists:gudang_stoks,no_gudang'],
            'no_ruangan' => ['required', 'exists:ruangans,no_ruangan'],
            'tanggal_keluar' => ['required', 'date'],
            'jumlah_keluar' => ['required', 'integer', 'min:1'],
            'keterangan_keluar' => ['nullable', 'string', 'max:50'],
        ];
    }

    // Definisikan semua relasi
    public function getRelations(): array
    {
        return [
            'gudangStok',
            'ruangan'
        ];
    }

    /**
     * Tambahkan Filter
     */
    public function filters(): array
    {
        return [
            // Filter berdasarkan Barang
            BelongsTo::make(
                'Barang',
                'gudangStok',
                formatted: fn(GudangStok $model) => $model->barangGudang->nama_barang_gudang
            )->searchable(),
            
            // Filter berdasarkan Ruangan
            BelongsTo::make(
                'Ruangan',
                'ruangan',
                formatted: fn(Ruangan $model) => $model->nama_ruangan
            )->searchable(),

            // Filter berdasarkan tanggal
            DateRange::make('Tanggal Keluar', 'tanggal_keluar')
                ->format('d-m-Y'),
        ];
    }

    /**
     * Searchable columns
     * Tambahkan 'jumlah_keluar'
     */
    public function search(): array
    {
        return ['no_gudang_keluar', 'tanggal_keluar', 'jumlah_keluar', 'keterangan_keluar'];
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
                fn() => route('report.gudang.keluar.pdf') . '?' . request()->getQueryString()
            )
                ->icon('heroicons.outline.printer')
                ->blank(),
            
            ActionButton::make(
                'Export Excel', 
                fn() => route('report.gudang.keluar.excel') . '?' . request()->getQueryString()
            )
                ->icon('heroicons.outline.table-cells')
                ->blank(),
        ];
    }
}