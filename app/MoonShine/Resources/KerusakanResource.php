<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kerusakan;
use App\Models\InventarisBarang; // Import model relasi
use App\Models\Barang; // Import Barang untuk menampilkan nama/jenis
use App\Models\Ruangan; // Import Ruangan untuk menampilkan nama

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Date;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea; // Gunakan Textarea untuk kerusakan/keterangan yg panjang
use MoonShine\Fields\Select;
use MoonShine\Fields\Relationships\BelongsTo;

class KerusakanResource extends ModelResource
{
    // Properti TANPA static
    protected string $model = Kerusakan::class;

    protected string $title = 'Permintaan Perbaikan'; // Judul sesuai aplikasi lama

    // Kolom yang tampil di relasi atau pencarian default
    public string $titleField = 'no_kerusakan'; // Tampilkan ID saja atau detail kerusakan

    // Urutan menu (Misalnya setelah Inventaris Barang)
    protected int $priority = 6;

    // Field utama
    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No', 'no_kerusakan')->sortable(),

                Date::make('Tanggal Lapor', 'tanggal')
                    ->format('d-m-Y')
                    ->required()
                    ->sortable(),

                // Relasi ke InventarisBarang (Menampilkan detail barang & ruangan)
                BelongsTo::make('Inventaris Barang', 'inventarisBarang',
                    // Closure untuk format tampilan
                    function(InventarisBarang $inv) {
                        $barang = $inv->barang ? ($inv->barang->jenis_barang . ' ' . $inv->barang->nama_barang) : 'N/A';
                        $ruangan = $inv->ruangan->nama_ruangan ?? 'N/A';
                        return "{$barang} - {$ruangan} (Inv: {$inv->getKey()})"; // Tampilkan detail
                    }
                )
                    ->searchable() // Memungkinkan pencarian (misal berdasarkan nama barang/ruangan di relasi)
                    ->required(),

                Textarea::make('Detail Kerusakan', 'kerusakan') // Gunakan Textarea
                    ->required(),

                Select::make('Status', 'status_kerusakan')
                    ->options([ // Opsi status dari data SQL & fungsi.php
                        'Belum diperbaiki' => 'Belum diperbaiki',
                        'Sudah diperbaiki' => 'Sudah diperbaiki',
                        'Tidak bisa diperbaiki' => 'Tidak bisa diperbaiki',
                        'Barang dibawa teknisi' => 'Barang dibawa teknisi', // Opsi ini ada di form perbaikan asli
                    ])
                    ->required()
                    ->sortable(),

                Textarea::make('Keterangan', 'keterangan_kerusakan') // Gunakan Textarea
                    ->nullable()
                    ->hideOnIndex(),
            ])
        ];
    }

    // Aturan validasi
    public function rules(Model $item): array
    {
        return [
            'tanggal' => ['required', 'date'],
            'no_inventaris' => ['required', 'exists:inventaris_barangs,no_inventaris'],
            'kerusakan' => ['required', 'string', 'max:100'],
            'status_kerusakan' => ['required', 'string', 'max:25'],
            'keterangan_kerusakan' => ['nullable', 'string', 'max:50'],
        ];
    }

    // Searchable columns
    public function search(): array
    {
        // Bisa cari berdasarkan ID, Tanggal, Detail Kerusakan, Status, Keterangan
        // Pencarian barang/ruangan ditangani BelongsTo
        return ['no_kerusakan', 'tanggal', 'kerusakan', 'status_kerusakan', 'keterangan_kerusakan'];
    }

    // Definisikan relasi agar BelongsTo berfungsi
    public function getRelations(): array
    {
        return [
            'inventarisBarang' => [ // Relasi utama
                'barang',  // Relasi di dalam inventarisBarang
                'ruangan', // Relasi di dalam inventarisBarang
                'no_inventaris', // Kolom PK inventarisBarang
                'nama_barang',   // Kolom dari Barang
                'jenis_barang',  // Kolom dari Barang
                'nama_ruangan',  // Kolom dari Ruangan
            ]
        ];
    }

    // filters() and actions() can be added here if needed
}