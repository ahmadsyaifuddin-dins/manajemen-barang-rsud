<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang; // Import model Barang
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('barangs')->delete();
        DB::statement('ALTER TABLE barangs AUTO_INCREMENT = 1;');

        $barangs = [
            ['nama_barang' => 'Epson L120', 'jenis_barang' => 'Printer', 'kategori_barang' => 'Elektronik', 'keterangan_barang' => null],
            ['nama_barang' => 'Epson L220', 'jenis_barang' => 'Printer', 'kategori_barang' => 'Elektronik', 'keterangan_barang' => null],
            ['nama_barang' => 'Epson L3110', 'jenis_barang' => 'Printer', 'kategori_barang' => 'Elektronik', 'keterangan_barang' => null],
            ['nama_barang' => 'Epson L1110', 'jenis_barang' => 'Printer', 'kategori_barang' => 'Elektronik', 'keterangan_barang' => null],
            ['nama_barang' => 'Epson Lx-310', 'jenis_barang' => 'Printer', 'kategori_barang' => 'Elektronik', 'keterangan_barang' => null],
            ['nama_barang' => 'Printer L360', 'jenis_barang' => 'Printer', 'kategori_barang' => 'Elektronik', 'keterangan_barang' => null],
            ['nama_barang' => 'Brother DCP T710W', 'jenis_barang' => 'Printer', 'kategori_barang' => 'Elektronik', 'keterangan_barang' => null],
            ['nama_barang' => 'Brother DCP T720DW', 'jenis_barang' => 'Printer', 'kategori_barang' => 'Elektronik', 'keterangan_barang' => null],
            ['nama_barang' => 'Lenovo IDEACENTER AIO 330-20A', 'jenis_barang' => 'Komputer All In One', 'kategori_barang' => 'Elektronik', 'keterangan_barang' => null],
            ['nama_barang' => 'Axioo AIO', 'jenis_barang' => 'Komputer All In One', 'kategori_barang' => 'Elektronik', 'keterangan_barang' => null],
            // Tambahkan data barang lainnya...
        ];

        // Masukkan data
        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
        DB::table('barangs')->insert($barangs); // Alternatif Query Builder
    }
}