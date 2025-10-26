<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Barang; // Import model Barang

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Select;
// use MoonShine\Fields\Date; // Jika pakai timestamps


class BarangResource extends ModelResource
{
    // Properti TANPA static (mengikuti konvensi default generator v2)
    protected string $model = Barang::class;

    protected string $title = 'Barang'; // Judul singular

    // Kolom yang tampil di relasi
    public string $titleField = 'nama_barang';

    // Urutan menu (setelah Ruangan)
    protected int $priority = 2; // TANPA static

    // Field utama (Index, Form, Detail)
    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No Barang', 'no_barang')->sortable(), // Label diubah, kolom tetap no_barang

                Text::make('Nama Barang', 'nama_barang')
                    ->required()
                    ->sortable(),

                Text::make('Jenis Barang', 'jenis_barang')
                    ->required()
                    ->sortable(),

                Select::make('Kategori Barang', 'kategori_barang') // Gunakan Select
                    ->options([ // Definisikan opsi
                        'Elektronik' => 'Elektronik',
                        'Suku Cadang' => 'Suku Cadang',
                        'Barang Habis Pakai' => 'Barang Habis Pakai',
                    ])
                    ->required()
                    ->sortable(),

                Text::make('Keterangan', 'keterangan_barang')
                    ->hideOnIndex(), // Sembunyikan di tabel index (opsional)

                // Contoh jika pakai timestamps
                // Date::make('Created At', 'created_at')
                //     ->format('d.m.Y H:i')
                //     ->hideOnForm()
                //     ->sortable(),
                // Date::make('Updated At', 'updated_at')
                //      ->format('d.m.Y H:i')
                //      ->hideOnForm()
                //      ->sortable(),
            ])
        ];
    }

    // Aturan validasi
    public function rules(Model $item): array
    {
        return [
            'nama_barang' => ['required', 'string', 'max:30'],
            'jenis_barang' => ['required', 'string', 'max:20'],
            'kategori_barang' => ['required', 'string', 'max:50'],
            'keterangan_barang' => ['nullable', 'string', 'max:50'], // Boleh kosong
        ];
    }

    // Searchable columns (Opsional)
    public function search(): array
    {
        return ['no_barang', 'nama_barang', 'jenis_barang', 'kategori_barang'];
    }

    // filters() and actions() can be added here if needed
}