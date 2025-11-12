<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use MoonShine\Pages\Page;
use MoonShine\Decorations\Block;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;

class ReportPage extends Page
{
    public function title(): string
    {
        return 'Pusat Laporan';
    }

    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    /**
     * GANTI METHOD INI DENGAN KODE BARU
     */
    public function components(): array
    {
        return [
            Grid::make([
                // --- KOLOM KIRI ---
                Column::make([
                    Block::make('Laporan Inventaris & Gudang', [
                        // SATU Grid untuk semua tombol di blok ini
                        Grid::make([
                            
                            // Baris 1: Inventaris
                            Column::make([
                                ActionButton::make('PDF Inventaris', route('report.inventaris.pdf'))
                                    ->icon('heroicons.outline.printer')->blank()->secondary()
                            ])->columnSpan(6), // 6 dari 12 kolom
                            
                            Column::make([
                                ActionButton::make('Excel Inventaris', route('report.inventaris.excel'))
                                    ->icon('heroicons.outline.table-cells')->blank()->secondary()
                            ])->columnSpan(6), // 6 dari 12 kolom

                            // Baris 2: Stok Gudang
                            Column::make([
                                ActionButton::make('PDF Stok Gudang', route('report.gudang.stok.pdf'))
                                    ->icon('heroicons.outline.printer')->blank()
                            ])->columnSpan(6),
                            
                            Column::make([
                                ActionButton::make('Excel Stok Gudang', route('report.gudang.stok.excel'))
                                    ->icon('heroicons.outline.table-cells')->blank()
                            ])->columnSpan(6),

                            // Baris 3: Barang Masuk
                            Column::make([
                                ActionButton::make('PDF Barang Masuk', route('report.gudang.masuk.pdf'))
                                    ->icon('heroicons.outline.printer')->blank()
                            ])->columnSpan(6),
                            
                            Column::make([
                                ActionButton::make('Excel Barang Masuk', route('report.gudang.masuk.excel'))
                                    ->icon('heroicons.outline.table-cells')->blank()
                            ])->columnSpan(6),

                            // Baris 4: Barang Keluar
                            Column::make([
                                ActionButton::make('PDF Barang Keluar', route('report.gudang.keluar.pdf'))
                                    ->icon('heroicons.outline.printer')->blank()
                            ])->columnSpan(6),
                            
                            Column::make([
                                ActionButton::make('Excel Barang Keluar', route('report.gudang.keluar.excel'))
                                    ->icon('heroicons.outline.table-cells')->blank()
                            ])->columnSpan(6),
                            
                            // Column::make([
                            //     ActionButton::make('PDF Kerusakan', route('report.kerusakan.pdf'))
                            //         ->icon('heroicons.outline.printer')->blank()
                            //         ->customAttributes(['class' => 'btn-warning'])
                            // ])->columnSpan(6),

                            // Column::make([
                            //     ActionButton::make('Excel Kerusakan', route('report.kerusakan.excel'))
                            //         ->icon('heroicons.outline.table-cells')->blank()
                            //         ->customAttributes(['class' => 'btn-warning'])
                            // ])->columnSpan(6),
                            
                        ])
                    ])
                ])->columnSpan(6), // Setengah Halaman
 
                
                // --- KOLOM KANAN --- Uncomment ini jika mau dipake buat skripsi minimal 8 laporan wkwk
                Column::make([
                    Block::make('Laporan Alur Perbaikan', [
                        // SATU Grid untuk semua tombol di blok ini
                        Grid::make([
                            
                            // Baris 1: Kerusakan (Kuning)
                            Column::make([
                                ActionButton::make('PDF Kerusakan', route('report.kerusakan.pdf'))
                                    ->icon('heroicons.outline.printer')->blank()
                                    ->customAttributes(['class' => 'btn-warning'])
                            ])->columnSpan(6),
                            
                            Column::make([
                                ActionButton::make('Excel Kerusakan', route('report.kerusakan.excel'))
                                    ->icon('heroicons.outline.table-cells')->blank()
                                    ->customAttributes(['class' => 'btn-warning'])
                            ])->columnSpan(6),

                            // Baris 2: Perbaikan (Hijau)
                            Column::make([
                                ActionButton::make('PDF Perbaikan', route('report.perbaikan.pdf'))
                                    ->icon('heroicons.outline.printer')->blank()
                                    ->customAttributes(['class' => 'btn-success'])
                            ])->columnSpan(6),
                            
                            Column::make([
                                ActionButton::make('Excel Perbaikan', route('report.perbaikan.excel'))
                                    ->icon('heroicons.outline.table-cells')->blank()
                                    ->customAttributes(['class' => 'btn-success'])
                            ])->columnSpan(6),

                            // Baris 3: Serah Terima (Hijau)
                            Column::make([
                                ActionButton::make('PDF Serah Terima', route('report.serah.pdf'))
                                    ->icon('heroicons.outline.printer')->blank()
                                    ->customAttributes(['class' => 'btn-success'])
                            ])->columnSpan(6),
                            
                            Column::make([
                                ActionButton::make('Excel Serah Terima', route('report.serah.excel'))
                                    ->icon('heroicons.outline.table-cells')->blank()
                                    ->customAttributes(['class' => 'btn-success'])
                            ])->columnSpan(6),

                            // Baris 4: Rusak Berat (Merah)
                            Column::make([
                                ActionButton::make('PDF Rusak Berat', route('report.rusak.pdf'))
                                    ->icon('heroicons.outline.printer')->blank()
                                    ->customAttributes(['class' => 'btn-danger'])
                            ])->columnSpan(6),
                            
                            Column::make([
                                ActionButton::make('Excel Rusak Berat', route('report.rusak.excel'))
                                    ->icon('heroicons.outline.table-cells')->blank()
                                    ->customAttributes(['class' => 'btn-danger'])
                            ])->columnSpan(6),
                        ])
                    ])
                ])->columnSpan(6), // Setengah Halaman
            ])
        ];
    }
}