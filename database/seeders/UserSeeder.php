<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Import model User
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Import Hash

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');

    
        // Tambahkan user lain dari SQL (jika perlu)
         User::create([
            'no_ruangan' => 1, // Sesuaikan no_ruangan
            'nama_user' => 'Keperawatan',
            'email' => 'keperawatan@gmail.com', // Ganti dengan email valid
            'password' => Hash::make('password'), // Hash password!
            'id_user' => 'keperawatan',
            'role_user' => null
        ]);
         User::create([
            'no_ruangan' => 1, // Sesuaikan no_ruangan
            'nama_user' => 'Admin',
            'email' => 'admin@gmail.com', // Ganti dengan email valid
            'password' => Hash::make('password'), // Hash password!
            'id_user' => 'admin',
            'role_user' => 'admin'
        ]);
         User::create([
            'no_ruangan' => 10, // Sesuaikan no_ruangan
            'nama_user' => 'Rekam Medis',
            'email' => 'rm@gmail.com', // Ganti dengan email valid
            'password' => Hash::make('password'), // Hash password!
            'id_user' => 'rm',
            'role_user' => null
        ]);
        // ... tambahkan user lainnya
    }
}