<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) { // Nama tabel jadi 'barangs' (plural)
            $table->id('no_barang'); // Menggunakan 'no_barang' sebagai primary key auto-increment
            $table->string('nama_barang', 30);
            $table->string('jenis_barang', 20);
            $table->string('kategori_barang', 50);
            $table->string('keterangan_barang', 50)->nullable(); // Keterangan bisa kosong
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};