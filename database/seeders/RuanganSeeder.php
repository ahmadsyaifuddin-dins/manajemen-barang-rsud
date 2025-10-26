<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ruangan; // Import model Ruangan
use Illuminate\Support\Facades\DB; // Import DB Facade

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama (opsional, hati-hati jika data sudah ada)
        DB::table('ruangans')->delete();

        // Reset auto-increment (opsional, tergantung database)
        DB::statement('ALTER TABLE ruangans AUTO_INCREMENT = 1;'); // Sesuaikan jika DB bukan MySQL

        $ruangans = [
            ['nama_ruangan' => 'SUBAG Umum dan RT', 'kepala_ruangan' => '..'],
            ['nama_ruangan' => 'Bidang Pelayanan', 'kepala_ruangan' => '..'],
            ['nama_ruangan' => 'Bidang Keperawatan', 'kepala_ruangan' => '.'],
            ['nama_ruangan' => 'Bidang Keuangan', 'kepala_ruangan' => '..'],
            ['nama_ruangan' => 'SUBAG Hukum dan Humas', 'kepala_ruangan' => '..'],
            ['nama_ruangan' => 'Instalasi JKN', 'kepala_ruangan' => '..'],
            ['nama_ruangan' => 'Instalasi Kesling', 'kepala_ruangan' => '.'],
            ['nama_ruangan' => 'IPSRS', 'kepala_ruangan' => '.'],
            ['nama_ruangan' => 'TIM IT', 'kepala_ruangan' => '.'],
            ['nama_ruangan' => 'Rekam Medis', 'kepala_ruangan' => '.'],
            // Tambahkan data ruangan lainnya dari file SQL...
            ['nama_ruangan' => 'Kasir', 'kepala_ruangan' => '.'],
            ['nama_ruangan' => 'Reseptionis', 'kepala_ruangan' => '.'],
            ['nama_ruangan' => 'Laboratorium', 'kepala_ruangan' => '.'],
            ['nama_ruangan' => 'IGD', 'kepala_ruangan' => '.'],
            ['nama_ruangan' => 'Ruang Isolasi', 'kepala_ruangan' => '..'],

        ];

        // Masukkan data menggunakan Model
        foreach ($ruangans as $ruangan) {
            Ruangan::create($ruangan);
        }

        // Atau masukkan data menggunakan Query Builder (lebih cepat untuk data banyak)
        DB::table('ruangans')->insert($ruangans);
    }
}