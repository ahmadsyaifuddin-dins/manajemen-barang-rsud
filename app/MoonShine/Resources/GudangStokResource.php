<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\GudangStok;
use App\Models\BarangGudang; // Import model relasi

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Number; // Field untuk angka (jumlah)
use MoonShine\Fields\Relationships\BelongsTo;

class GudangStokResource extends ModelResource
{
    // Properti TANPA static
    protected string $model = GudangStok::class;

    protected string $title = 'Stok Gudang'; // Judul

    // Kolom yang tampil di relasi atau pencarian default
    // Menampilkan nama barang gudang akan lebih informatif
    public string $titleField = 'barangGudang.nama_barang_gudang'; // Gunakan relasi

    // Urutan menu (Misalnya, di dalam grup Gudang nanti)
    protected int $priority = 1;

    // Field utama
    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No Stok', 'no_gudang')->sortable(), // Primary Key

                // Relasi ke BarangGudang
                BelongsTo::make('Barang Gudang', 'barangGudang', fn(BarangGudang $model) => $model->nama_barang_gudang . ' (' . $model->kategori_barang_gudang . ')') // Tampilkan nama & kategori
                    ->searchable()
                    ->required(),

                Text::make('Jenis Barang', 'barangGudang.jenis_barang_gudang') // Akses via relasi
                    ->hideOnForm() // Sembunyikan di form (karena sudah ada di BelongsTo)
                    ->hideOnDetail() // Sembunyikan di detail (karena sudah ada di BelongsTo)
                    ->sortable(), // Opsional: Coba sortable, mungkin perlu penyesuaian

                Text::make('Kategori Barang', 'barangGudang.kategori_barang_gudang') // Akses via relasi
                    ->hideOnForm() // Sembunyikan di form
                    ->hideOnDetail() // Sembunyikan di detail
                    ->sortable(), // Opsional

                Number::make('Jumlah Barang', 'jumlah_barang') // Field Angka
                    ->required()
                    ->sortable(),

                Text::make('Keterangan', 'keterangan_gudang')
                    ->nullable()
                    ->hideOnIndex(),
            ])
        ];
    }

    // Aturan validasi
    public function rules(Model $item): array
    {
        return [
            'no_barang_gudang' => ['required', 'exists:barang_gudangs,no_barang_gudang'],
            'jumlah_barang' => ['required', 'integer', 'min:0'], // Jumlah harus angka >= 0
            'keterangan_gudang' => ['nullable', 'string', 'max:50'],
        ];
    }

    // Searchable columns
    public function search(): array
    {
        // Bisa cari berdasarkan ID Stok atau Keterangan
        // Pencarian nama barang gudang ditangani oleh BelongsTo
        return ['no_gudang', 'keterangan_gudang'];
    }

    // Definisikan relasi agar BelongsTo berfungsi
    public function getRelations(): array
    {
        return [
            'barangGudang', // Nama method relasi di Model GudangStok
        ];
    }

    // filters() and actions() can be added here if needed
}
