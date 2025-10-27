<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\GudangStok;
use App\Models\BarangGudang;

use MoonShine\ActionButtons\ActionButton;
use MoonShine\Handlers\ExportHandler;
use MoonShine\QueryTags\QueryTag;

use MoonShine\Fields\DateRange;
use MoonShine\Fields\Select;
use MoonShine\Fields\Range;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;

class GudangStokResource extends ModelResource
{
    protected string $model = GudangStok::class;
    protected string $title = 'Stok Gudang';
    public string $titleField = 'no_gudang';
    protected int $priority = 1;

    protected ?string $activeQueryTag = 'Semua';

    /**
     * Override query default untuk selalu load relasi barangGudang.
     * KRUSIAL agar di index & export, BelongsTo tampilkan nama (bukan ID).
     */
    public function query(): Builder
    {
        /** @var Builder $query */
        $query = parent::query();
        return $query->with(['barangGudang']);
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No Stok', 'no_gudang')
                    ->sortable()
                    ->showOnExport(),

                BelongsTo::make(
                    'Barang Gudang',
                    'barangGudang',
                    formatted: fn(BarangGudang $model) => $model->nama_barang_gudang . ' (' . $model->kategori_barang_gudang . ')'
                )
                    ->searchable()
                    ->required()
                    ->showOnExport(),

                Text::make('Jenis Barang', 'barangGudang.jenis_barang_gudang')
                    ->hideOnForm()
                    ->hideOnDetail()
                    ->sortable()
                    ->showOnExport(),

                Text::make('Kategori Barang', 'barangGudang.kategori_barang_gudang')
                    ->hideOnForm()
                    ->hideOnDetail()
                    ->sortable()
                    ->showOnExport(),

                Number::make('Jumlah Barang', 'jumlah_barang')
                    ->required()
                    ->sortable()
                    ->showOnExport(),

                Text::make('Keterangan', 'keterangan_gudang')
                    ->nullable()
                    ->hideOnIndex()
                    ->showOnExport(),
            ])
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'no_barang_gudang' => ['required', 'exists:barang_gudangs,no_barang_gudang'],
            'jumlah_barang' => ['required', 'integer', 'min:0'],
            'keterangan_gudang' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function search(): array
    {
        return ['no_gudang', 'keterangan_gudang'];
    }

    public function getRelations(): array
    {
        return ['barangGudang'];
    }

    public function filters(): array
    {
        return [
            // Filter 1: Dropdown berdasarkan Relasi Nama Barang
            BelongsTo::make(
                'Nama Barang',
                'barangGudang',
                formatted: fn($model) => $model->nama_barang_gudang
            )->searchable(),

            // Filter 4: Range Jumlah Barang
            Range::make('Jumlah Barang', 'jumlah_barang')
                ->fromTo('jumlah_barang_from', 'jumlah_barang_to'),

            // Filter 5: Rentang Tanggal Dibuat
            DateRange::make('Tanggal Dibuat', 'created_at')
                ->format('d-m-Y'),
        ];
    }

    /**
     * Query Tags untuk filter Jenis & Kategori sebagai tombol cepat
     */
    public function queryTags(): array
    {
        $jenisList = BarangGudang::distinct()
            ->whereNotNull('jenis_barang_gudang')
            ->orderBy('jenis_barang_gudang')
            ->pluck('jenis_barang_gudang');

        $kategoriList = BarangGudang::distinct()
            ->whereNotNull('kategori_barang_gudang')
            ->orderBy('kategori_barang_gudang')
            ->pluck('kategori_barang_gudang');

        $tags = [
            QueryTag::make(
                'Semua', // Label
                fn(Builder $query) => $query // Query tanpa filter
            ) // Set sebagai default
        ];

        // Tambahkan tag untuk setiap jenis
        foreach ($jenisList as $jenis) {
            $tags[] = QueryTag::make(
                "Jenis: {$jenis}",
                fn(Builder $query) => $query->whereHas(
                    'barangGudang',
                    fn($q) => $q->where('jenis_barang_gudang', $jenis)
                )
            )->icon('heroicons.outline.tag');
        }

        // Tambahkan tag untuk setiap kategori
        foreach ($kategoriList as $kategori) {
            $tags[] = QueryTag::make(
                "Kategori: {$kategori}",
                fn(Builder $query) => $query->whereHas(
                    'barangGudang',
                    fn($q) => $q->where('kategori_barang_gudang', $kategori)
                )
            )->icon('heroicons.outline.folder');
        }

        return $tags;
    }

    /**
     * Built-in Export Excel (XLSX).
     * Return null untuk menyembunyikan tombol bawaan.
     */
    public function export(): ?ExportHandler
    {
        return null;
    }

    /**
     * Page-level actions: Tombol di atas tabel index.
     * - Reset Filter: Clear semua filter
     * - Cetak PDF: Export ke PDF dengan filter aktif
     * - Export Excel: Export ke Excel dengan filter aktif
     */
    public function actions(): array
    {
        return [
            // Tombol Reset Filter
            ActionButton::make('Reset Filter', function () {
                return request()->url();
            })
                ->icon('heroicons.outline.arrow-path')
                ->secondary(),

            // Tombol Cetak PDF
            ActionButton::make('Cetak PDF', function () {
                return route('report.gudang.stok.pdf') . '?' . request()->getQueryString();
            })
                ->icon('heroicons.outline.printer')
                ->blank(),

            // Tombol Export Excel
            ActionButton::make('Export Excel', function () {
                return route('report.gudang.stok.excel') . '?' . request()->getQueryString();
            })
                ->icon('heroicons.outline.table-cells')
                ->blank(),
        ];
    }
}
