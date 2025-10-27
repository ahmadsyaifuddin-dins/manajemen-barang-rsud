<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\InventarisBarang;
use App\Models\Barang; // Import model Barang
use App\Models\Ruangan; // Import model Ruangan

use MoonShine\Resources\ModelResource;

use MoonShine\Fields\DateRange;

use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Date;   // Import Date field
use MoonShine\Fields\Select; // Import Select field
use MoonShine\Fields\Relationships\BelongsTo; // Import BelongsTo
use MoonShine\ActionButtons\ActionButton; // Import ActionButton
use MoonShine\Handlers\ExportHandler; // Import untuk built-in export

class InventarisBarangResource extends ModelResource
{
    protected string $model = InventarisBarang::class;

    protected string $title = 'Inventaris Barang';

    public string $titleField = 'no_inventaris';

    protected int $priority = 5;

    /**
     * Override query default untuk selalu load relasi ruangan & barang.
     * Ini KRUSIAL agar di index & export, BelongsTo tampilkan nama/jenis (bukan ID),
     * karena formatter closure di BelongsTo::make() akan dipanggil otomatis.
     */
    public function query(): Builder
    {
        /** @var Builder $query */
        $query = parent::query();
        return $query->with(['ruangan', 'barang']);
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No Inventaris', 'no_inventaris')
                    ->sortable()
                    ->showOnExport(), // Export kolom ini

                BelongsTo::make('Ruangan', 'ruangan', formatted: fn(Ruangan $model) => $model->nama_ruangan)
                    ->searchable()
                    ->required()
                    ->showOnExport(), // Otomatis export nama_ruangan (karena relasi loaded & formatted closure)

                BelongsTo::make('Barang', 'barang', formatted: fn(Barang $model) => $model->nama_barang . ' (' . $model->jenis_barang . ')')
                    ->searchable()
                    ->required()
                    ->showOnExport(), // Otomatis export "nama (jenis)" 

                Text::make('Kode Barang', 'kode_barang')
                    ->nullable()
                    ->sortable()
                    ->showOnExport(),

                Date::make('Tanggal Masuk', 'tanggal_masuk')
                    ->format('d-m-Y')
                    ->required()
                    ->sortable()
                    ->showOnExport(),

                Select::make('Kondisi', 'kondisi')
                    ->options([
                        'Baik' => 'Baik',
                        'Rusak' => 'Rusak',
                        'Belum' => 'Belum',
                    ])
                    ->required()
                    ->sortable()
                    ->showOnExport(),

                Text::make('Keterangan', 'keterangan_inventaris')
                    ->nullable()
                    ->hideOnIndex() // Tetap sembunyi di index
                    ->showOnExport(), // Tapi muncul di export Excel
            ])
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'no_ruangan' => ['required', 'exists:ruangans,no_ruangan'],
            'no_barang' => ['required', 'exists:barangs,no_barang'],
            'kode_barang' => ['nullable', 'string', 'max:25'],
            'tanggal_masuk' => ['required', 'date'],
            'kondisi' => ['required', 'string', 'max:5'],
            'keterangan_inventaris' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function search(): array
    {
        return ['no_inventaris', 'kode_barang', 'keterangan_inventaris'];
    }

    public function getRelations(): array
    {
        return ['ruangan', 'barang'];
    }

    public function filters(): array
    {
        return [
            // Filter Dropdown berdasarkan Relasi Ruangan
            // Menggunakan MoonShine\Fields\Relationships\BelongsTo
            BelongsTo::make('Ruangan', 'ruangan', formatted: fn($model) => $model->nama_ruangan)
                ->searchable(),

            // Filter Dropdown untuk Kondisi
            // Menggunakan MoonShine\Fields\Select
            Select::make('Kondisi', 'kondisi')
                ->options([
                    'Baik' => 'Baik',
                    'Rusak' => 'Rusak',
                    'Belum' => 'Belum',
                ]),
                
            // Filter Rentang Tanggal untuk Tanggal Masuk
            // Menggunakan MoonShine\Fields\DateRange
            DateRange::make('Tanggal Masuk', 'tanggal_masuk')
                ->format('d-m-Y'),
        ];
    }

    /**
     * Built-in Export Excel (XLSX).
     * Tombol "Export Excel" otomatis muncul di index page.
     * Karena relasi di-load via query(), nama ruangan/barang akan otomatis diekspor (bukan ID).
     * File disimpan sementara di storage/app/public/exports (accessible via symlink storage:link).
     * Default format: XLSX (tidak perlu ->xlsx(), karena undefined; gunakan ->csv() jika ingin ganti ke CSV).
     */
    public function export(): ?ExportHandler
    {
        return null; // Ini akan menyembunyikan tombol "Export Excel" bawaan
    }
    

    /**
     * Page-level actions: Tombol di atas tabel index.
     * - Cetak PDF: Link ke route custom kamu (global export semua data).
     * Built-in Excel sudah handle tombolnya sendiri via export(), jadi tidak perlu duplikat.
     */
    public function actions(): array
    {
        return [
            // Tombol PDF Kustom
            ActionButton::make(
                'Cetak PDF', 
                fn() => route('report.inventaris.pdf') . '?' . request()->getQueryString()
            )
                ->icon('heroicons.outline.printer')
                ->blank(),
            
            ActionButton::make(
                'Export Excel', 
                fn() => route('report.inventaris.excel') . '?' . request()->getQueryString()
            )
                ->icon('heroicons.outline.table-cells')
                ->blank(),
        ];
    }
}
