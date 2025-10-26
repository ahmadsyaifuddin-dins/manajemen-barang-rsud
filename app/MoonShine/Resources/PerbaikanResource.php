<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Perbaikan;
use App\Models\Kerusakan; // Import model relasi
use App\Models\InventarisBarang; // Untuk detail barang
use App\Models\Barang; // Untuk detail barang
use App\Models\Ruangan; // Untuk detail barang

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Date;
use MoonShine\Fields\Textarea;
use MoonShine\Fields\Select;
use MoonShine\Fields\Relationships\BelongsTo;

class PerbaikanResource extends ModelResource
{
    // Properti TANPA static
    protected string $model = Perbaikan::class;

    protected string $title = 'Perbaikan Barang'; // Judul

    // Kolom yang tampil di relasi atau pencarian default
    public string $titleField = 'no_perbaikan';

    // Urutan menu (setelah Permintaan Perbaikan)
    protected int $priority = 7;

    // Field utama
    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No', 'no_perbaikan')->sortable(),

                // Relasi ke Kerusakan (Menampilkan detail kerusakan & barang terkait)
                BelongsTo::make('Detail Kerusakan', 'kerusakan',
                    // Closure untuk format tampilan
                    function(Kerusakan $kerusakan) {
                        $inventaris = $kerusakan->inventarisBarang;
                        $barang = $inventaris?->barang ? ($inventaris->barang->jenis_barang . ' ' . $inventaris->barang->nama_barang) : 'N/A';
                        $ruangan = $inventaris?->ruangan->nama_ruangan ?? 'N/A';
                        $detailKerusakan = $kerusakan->kerusakan ?? 'N/A';
                        return "{$barang} ({$ruangan}) - Rusak: {$detailKerusakan}"; // Tampilkan detail
                    }
                )
                    ->searchable() // Memungkinkan pencarian (misal berdasarkan detail kerusakan atau nama barang via relasi)
                    ->required(),

                Date::make('Tanggal Perbaikan', 'tanggal_perbaikan')
                    ->format('d-m-Y')
                    ->required()
                    ->sortable(),

                Select::make('Status Perbaikan', 'status')
                    ->options([ // Opsi dari Pbarang.php & fungsi.php
                        'Barang dibawa teknisi' => 'Barang dibawa teknisi',
                        'Sudah diperbaiki' => 'Sudah diperbaiki',
                        'Tidak bisa diperbaiki' => 'Tidak bisa diperbaiki',
                    ])
                    ->required()
                    ->sortable(),

                Select::make('Kondisi Barang Setelah Perbaikan', 'kondisi_perbaikan')
                     ->options([ // Opsi dari Pbarang.php & fungsi.php
                        'Belum' => 'Belum Selesai/Dicek', // Label diperjelas
                        'Baik' => 'Baik',
                        'Rusak' => 'Rusak',
                    ])
                    ->required()
                    ->sortable(),

                Textarea::make('Keterangan Perbaikan', 'keterangan')
                    ->nullable()
                    ->hideOnIndex(),
            ])
        ];
    }

    // Aturan validasi
    public function rules(Model $item): array
    {
        return [
            'no_kerusakan' => ['required', 'exists:kerusakans,no_kerusakan'],
            'tanggal_perbaikan' => ['required', 'date'],
            'status' => ['required', 'string', 'max:25'],
            'kondisi_perbaikan' => ['required', 'string', 'max:5'], // Sesuaikan panjang jika perlu
            'keterangan' => ['nullable', 'string', 'max:50'],
        ];
    }

    // Searchable columns
    public function search(): array
    {
        return ['no_perbaikan', 'tanggal_perbaikan', 'status', 'kondisi_perbaikan', 'keterangan'];
    }

    // Definisikan relasi agar BelongsTo berfungsi
    public function getRelations(): array
    {
        return [
            'kerusakan' => [ // Relasi utama
                'inventarisBarang' => [ // Relasi di dalam kerusakan
                    'barang', // Relasi di dalam inventarisBarang
                    'ruangan' // Relasi di dalam inventarisBarang
                ],
                'no_kerusakan', // PK kerusakan
                'kerusakan' // Kolom detail kerusakan
            ],
        ];
    }

    // filters() and actions() can be added here if needed

    // --- CATATAN PENTING ---
    // Logika untuk update 'status_kerusakan' di model Kerusakan
    // dan update 'kondisi' di model InventarisBarang
    // sebaiknya ditangani menggunakan Model Observer (PerbaikanObserver)
    // mirip seperti logika di fungsi.php
}