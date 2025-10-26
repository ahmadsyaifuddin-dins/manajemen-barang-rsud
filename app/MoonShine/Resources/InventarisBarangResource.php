<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\InventarisBarang;
use App\Models\Barang; // Import model Barang
use App\Models\Ruangan; // Import model Ruangan

// --- Namespace v2 ---
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Date;   // Import Date field
use MoonShine\Fields\Select; // Import Select field
use MoonShine\Fields\Relationships\BelongsTo; // Import BelongsTo
// --- End Namespace v2 ---


class InventarisBarangResource extends ModelResource
{
    // Properti TANPA static
    protected string $model = InventarisBarang::class;

    protected string $title = 'Inventaris Barang'; // Judul

    // Kolom yang tampil di relasi atau pencarian default
    // Kita bisa gabungkan nama barang dan ruangan, tapi perlu definisikan get() method di model
    // Untuk sementara, kita pakai ID saja atau nama barang
    public string $titleField = 'no_inventaris'; // Atau sesuaikan nanti

    // Urutan menu (Misalnya, setelah Master Data)
    protected int $priority = 5;

    // Field utama
    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No Inventaris', 'no_inventaris')->sortable(),

                // Relasi ke Ruangan
                BelongsTo::make('Ruangan', 'ruangan', fn(Ruangan $model) => $model->nama_ruangan)
                    ->searchable() // Bisa cari nama ruangan
                    ->required(),

                // Relasi ke Barang
                BelongsTo::make('Barang', 'barang', fn(Barang $model) => $model->nama_barang . ' (' . $model->jenis_barang . ')') // Tampilkan nama & jenis
                    ->searchable() // Bisa cari nama barang
                    ->required(),

                // Kode Barang (mungkin nomor seri?)
                Text::make('Kode Barang', 'kode_barang')
                    ->nullable(), // Boleh kosong

                Date::make('Tanggal Masuk', 'tanggal_masuk')
                    ->format('d-m-Y') // Format tampilan tanggal
                    ->required()
                    ->sortable(),

                Select::make('Kondisi', 'kondisi')
                    ->options([
                        'Baik' => 'Baik',
                        'Rusak' => 'Rusak',
                        // Tambahkan 'Belum' jika diperlukan sesuai migrasi/data lama
                        'Belum' => 'Belum',
                    ])
                    ->required()
                    ->sortable(),

                Text::make('Keterangan', 'keterangan_inventaris')
                    ->nullable()
                    ->hideOnIndex(), // Sembunyikan di index
            ])
        ];
    }

    // Aturan validasi
    public function rules(Model $item): array
    {
        return [
            'no_ruangan' => ['required', 'exists:ruangans,no_ruangan'],
            'no_barang' => ['required', 'exists:barangs,no_barang'],
            'kode_barang' => ['nullable', 'integer'], // Sesuaikan jika tipe datanya beda
            'tanggal_masuk' => ['required', 'date'],
            'kondisi' => ['required', 'string', 'max:5'], // Sesuaikan max length jika perlu
            'keterangan_inventaris' => ['nullable', 'string', 'max:50'],
        ];
    }

    // Searchable columns
    public function search(): array
    {
        // Bisa cari berdasarkan ID, Kode, atau Keterangan
        // Pencarian relasi (ruangan, barang) biasanya ditangani otomatis oleh BelongsTo
        return ['no_inventaris', 'kode_barang', 'keterangan_inventaris'];
    }

    // Definisikan relasi agar Moonshine tahu cara mengambil data BelongsTo
    public function getRelations(): array
    {
        return [
            'ruangan', // Nama method relasi di Model InventarisBarang
            'barang'   // Nama method relasi di Model InventarisBarang
        ];
    }

    // filters() and actions() can be added here if needed
}