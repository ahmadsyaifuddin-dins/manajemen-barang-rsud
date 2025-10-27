<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\GudangKeluar;
use App\Models\GudangStok; // Import model relasi stok
use App\Models\Ruangan;   // Import model relasi ruangan

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Date;
use MoonShine\Fields\Number;
use MoonShine\Fields\Text;
use MoonShine\Fields\Relationships\BelongsTo;

class GudangKeluarResource extends ModelResource
{
    // Properti TANPA static
    protected string $model = GudangKeluar::class;

    protected string $title = 'Barang Keluar Gudang'; // Judul

    // Kolom yang tampil di relasi atau pencarian default
    public string $titleField = 'no_gudang_keluar';

    // Urutan menu (setelah Barang Masuk)
    protected int $priority = 3;

    // Field utama
    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No', 'no_gudang_keluar')->sortable(),

                // Relasi ke GudangStok (menampilkan nama barang gudang)
                BelongsTo::make('Barang Gudang', 'gudangStok',
                    fn(GudangStok $stok) => $stok->barangGudang->nama_barang_gudang ?? 'N/A'
                )
                    ->searchable()
                    ->required(),

                // Relasi ke Ruangan
                BelongsTo::make('Ruangan Tujuan', 'ruangan', fn(Ruangan $ruangan) => $ruangan->nama_ruangan)
                    ->searchable()
                    ->required(),

                Date::make('Tanggal Keluar', 'tanggal_keluar')
                    ->format('d-m-Y')
                    ->required()
                    ->sortable(),

                Number::make('Jumlah Keluar', 'jumlah_keluar')
                    ->required()
                    ->sortable(),

                Text::make('Keterangan', 'keterangan_keluar')
                    ->nullable()
                    ->hideOnIndex(),
            ])
        ];
    }

    // Aturan validasi
    public function rules(Model $item): array
    {
        // Validasi tambahan: jumlah keluar tidak boleh > stok (perlu Observer/Custom Rule)
        // Untuk sementara, validasi dasar:
        return [
            'no_gudang' => ['required', 'exists:gudang_stoks,no_gudang'],
            'no_ruangan' => ['required', 'exists:ruangans,no_ruangan'],
            'tanggal_keluar' => ['required', 'date'],
            'jumlah_keluar' => ['required', 'integer', 'min:1'], // Minimal 1
            'keterangan_keluar' => ['nullable', 'string', 'max:50'],
        ];
    }

    // Searchable columns
    public function search(): array
    {
        // Bisa cari berdasarkan ID Keluar, Tanggal, Keterangan
        // Pencarian nama barang & ruangan ditangani BelongsTo
        return ['no_gudang_keluar', 'tanggal_keluar', 'keterangan_keluar'];
    }

    // Definisikan relasi agar BelongsTo berfungsi
    public function getRelations(): array
    {
        return [
            'gudangStok' => ['nama_barang_gudang', 'no_barang_gudang'], // Sertakan kolom dari relasi barangGudang
            'ruangan', // Nama method relasi di Model GudangKeluar
        ];
    }

    // Sekali lagi, logika update stok sebaiknya di Observer
    // public function onBoot(): void { // ... }

    // filters() and actions() can be added here if needed
}