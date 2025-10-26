<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\BarangGudang; // Import model

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Select; // Import Select untuk Kategori


class BarangGudangResource extends ModelResource
{
    // Properti TANPA static
    protected string $model = BarangGudang::class;

    protected string $title = 'Barang Gudang'; // Judul

    // Kolom yang tampil di relasi
    public string $titleField = 'nama_barang_gudang';

    // Urutan menu (setelah Barang)
    protected int $priority = 3;

    // Field utama
    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No', 'no_barang_gudang')->sortable(),

                Text::make('Nama Barang Gudang', 'nama_barang_gudang')
                    ->required()
                    ->sortable(),

                Text::make('Jenis Barang Gudang', 'jenis_barang_gudang')
                    ->required()
                    ->sortable(),

                Select::make('Kategori Barang Gudang', 'kategori_barang_gudang') // Gunakan Select
                    ->options([ // Opsi dari file SQL
                        'Barang Habis Pakai' => 'Barang Habis Pakai',
                        'Suku Cadang' => 'Suku Cadang',
                    ])
                    ->required()
                    ->sortable(),
            ])
        ];
    }

    // Aturan validasi
    public function rules(Model $item): array
    {
        return [
            'nama_barang_gudang' => ['required', 'string', 'max:50'],
            'jenis_barang_gudang' => ['required', 'string', 'max:30'],
            'kategori_barang_gudang' => ['required', 'string', 'max:30'],
        ];
    }

    // Searchable columns (Opsional)
    public function search(): array
    {
        return ['no_barang_gudang', 'nama_barang_gudang', 'jenis_barang_gudang', 'kategori_barang_gudang'];
    }

    // filters() and actions() can be added here if needed
}