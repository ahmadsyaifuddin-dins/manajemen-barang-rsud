<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\GudangMasuk;
use App\Models\GudangStok; // Import model relasi
use App\Models\BarangGudang; // Import BarangGudang untuk menampilkan nama

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Date;
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

    // Field utama
    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No', 'no_gudang_masuk')->sortable(),

                // Relasi ke GudangStok (menampilkan nama barang gudang)
                BelongsTo::make('Barang Gudang', 'gudangStok',
                    // Closure untuk format tampilan: ambil nama barang dari relasi gudangStok ke barangGudang
                    fn(GudangStok $stok) => $stok->barangGudang->nama_barang_gudang ?? 'N/A'
                )
                    ->searchable() // Memungkinkan pencarian berdasarkan nama barang (melalui relasi)
                    ->required(),

                Date::make('Tanggal Masuk', 'tanggal_masuk')
                    ->format('d-m-Y')
                    ->required()
                    ->sortable(),

                Number::make('Jumlah Masuk', 'jumlah_masuk')
                    ->required()
                    ->sortable(),
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

    // Searchable columns
    public function search(): array
    {
        // Bisa cari berdasarkan ID Masuk atau Tanggal
        // Pencarian nama barang ditangani oleh BelongsTo
        return ['no_gudang_masuk', 'tanggal_masuk'];
    }

    // Definisikan relasi agar BelongsTo berfungsi
    public function getRelations(): array
    {
        return [
            'gudangStok' => ['nama_barang_gudang', 'no_barang_gudang'], // Sertakan kolom dari relasi barangGudang di dalam gudangStok
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