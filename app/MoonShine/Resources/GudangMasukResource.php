<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Builder; // Import Builder
use Illuminate\Database\Eloquent\Model;
use App\Models\GudangMasuk;
use App\Models\GudangStok; // Import model relasi

use MoonShine\ActionButtons\ActionButton;
use MoonShine\Handlers\ExportHandler; // Import ExportHandler
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;

use MoonShine\Fields\ID;
use MoonShine\Fields\Date;
use MoonShine\Fields\DateRange;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;

class GudangMasukResource extends ModelResource
{
    protected string $model = GudangMasuk::class;
    protected string $title = 'Barang Masuk Gudang';
    public string $titleField = 'no_gudang_masuk';
    protected int $priority = 2;

    public function query(): Builder
    {
        /** @var Builder $query */
        $query = parent::query();
        
        // Cukup eager load relasi.
        // Logika filter manual sudah tidak diperlukan
        // karena sudah ditangani oleh search() dan filters().
        return $query->with(['gudangStok.barangGudang']);
    }

    // Field utama (Kode Anda sudah sempurna)
    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No', 'no_gudang_masuk')->sortable()->showOnExport(),

                BelongsTo::make(
                    'Barang',
                    'gudangStok',
                    formatted: fn(GudangStok $model) => $model->barangGudang->nama_barang_gudang . ' (' . $model->barangGudang->kategori_barang_gudang . ')'
                )
                    ->searchable()
                    ->required()
                    ->showOnExport(),

                Date::make('Tanggal Masuk', 'tanggal_masuk')
                    ->format('d-m-Y')
                    ->required()
                    ->sortable()
                    ->showOnExport(),

                Number::make('Jumlah Masuk', 'jumlah_masuk')
                    ->required()
                    ->sortable()
                    ->showOnExport(),
            ])
        ];
    }

    // Rules (Kode Anda sudah sempurna)
    public function rules(Model $item): array
    {
        return [
            'no_gudang' => ['required', 'exists:gudang_stoks,no_gudang'],
            'tanggal_masuk' => ['required', 'date'],
            'jumlah_masuk' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Filter (Kode Anda sudah sempurna, tanpa Range)
     */
    public function filters(): array
    {
        return [
            // Filter berdasarkan relasi (pilih barang)
            BelongsTo::make(
                'Barang',
                'gudangStok',
                formatted: fn(GudangStok $model) => $model->barangGudang->nama_barang_gudang
            )->searchable(),

            // Filter berdasarkan tanggal
            DateRange::make('Tanggal Masuk', 'tanggal_masuk')
                ->format('d-m-Y'),
        ];
    }

    /**
     * Searchable columns
     * KITA TAMBAHKAN 'jumlah_masuk' DI SINI
     * agar search bar atas berfungsi
     */
    public function search(): array
    {
        return ['no_gudang_masuk', 'tanggal_masuk', 'jumlah_masuk'];
    }

    /**
     * Definisikan relasi agar BelongsTo berfungsi
     * Cukup nama relasinya saja
     */
    public function getRelations(): array
    {
        return [
            'gudangStok', 
        ];
    }

    // Matikan export bawaan
    public function export(): ?ExportHandler
    {
        return null;
    }

    // Tombol Aksi (Kode Anda sudah sempurna)
    public function actions(): array
    {
        return [
            ActionButton::make('Reset', request()->url())
                ->icon('heroicons.outline.arrow-path')
                ->secondary(),

            ActionButton::make(
                'Cetak PDF',
                fn() => route('report.gudang.masuk.pdf') . '?' . request()->getQueryString()
            )
                ->icon('heroicons.outline.printer')
                ->blank(),

            ActionButton::make(
                'Export Excel',
                fn() => route('report.gudang.masuk.excel') . '?' . request()->getQueryString()
            )
                ->icon('heroicons.outline.table-cells')
                ->blank(),
        ];
    }
}