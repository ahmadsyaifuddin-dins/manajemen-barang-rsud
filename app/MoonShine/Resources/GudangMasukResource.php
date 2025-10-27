<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Builder; // Import Builder
use Illuminate\Database\Eloquent\Model;
use App\Models\GudangMasuk;
use App\Models\GudangStok; // Import model relasi

use MoonShine\ActionButtons\ActionButton;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;

use MoonShine\Fields\ID;
use MoonShine\Fields\Date;
use MoonShine\Fields\DateRange;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;

class GudangMasukResource extends ModelResource
{
    // Properti TANPA static
    protected string $model = GudangMasuk::class;

    protected string $title = 'Barang Masuk Gudang'; // Judul

    // Kolom yang tampil di relasi atau pencarian default
    public string $titleField = 'no_gudang_masuk'; // Atau sesuaikan jika perlu

    // Urutan menu (setelah Stok Gudang)
    protected int $priority = 2;

    public function query(): Builder
    {
        /** @var Builder $query */
        $query = parent::query();
        $query->with(['gudangStok.barangGudang']);

        // Handle custom filter untuk jumlah_masuk range
        if (request()->filled('filters.jumlah_masuk_from')) {
            $query->where('jumlah_masuk', '>=', request('filters.jumlah_masuk_from'));
        }

        if (request()->filled('filters.jumlah_masuk_to')) {
            $query->where('jumlah_masuk', '<=', request('filters.jumlah_masuk_to'));
        }

        return $query;
    }

    // Field utama
    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No', 'no_gudang_masuk')->sortable()->showOnExport(),

                // Relasi ke GudangStok, tapi menampilkan nama BarangGudang
                BelongsTo::make(
                    'Barang',
                    'gudangStok',
                    // Format tampilan: "Nama Barang (Kategori)"
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

    // Aturan validasi
    public function rules(Model $item): array
    {
        // Validasi tambahan: jumlah masuk tidak boleh lebih besar dari stok saat diedit (logika ini lebih kompleks, mungkin perlu Observer)
        // Untuk sementara, validasi dasar:
        return [
            'no_gudang' => ['required', 'exists:gudang_stoks,no_gudang'],
            'tanggal_masuk' => ['required', 'date'],
            'jumlah_masuk' => ['required', 'integer', 'min:1'], // Minimal 1
        ];
    }

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

    // Searchable columns
    public function search(): array
    {
        // Bisa cari berdasarkan ID Masuk atau Tanggal
        // Pencarian nama barang ditangani oleh BelongsTo
        return ['no_gudang_masuk', 'tanggal_masuk', 'jumlah_masuk'];
    }

    // Definisikan relasi agar BelongsTo berfungsi
    public function getRelations(): array
    {
        return [
            'gudangStok' => ['nama_barang_gudang', 'no_barang_gudang'], // Sertakan kolom dari relasi barangGudang di dalam gudangStok
        ];
    }

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

    // Jika Anda ingin mengupdate stok saat create/update/delete GudangMasuk,
    // cara terbaik adalah menggunakan Model Observer.
    // public function onBoot(): void
    // {
    //     // Logika update stok bisa ditambahkan di sini (kurang ideal)
    //     // atau lebih baik gunakan Observer
    // }

    // filters() and actions() can be added here if needed
}