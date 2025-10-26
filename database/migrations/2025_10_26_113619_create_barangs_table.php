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
            // Laravel otomatis menambahkan created_at dan updated_at (timestamps)
            // Jika tidak dibutuhkan, tambahkan $table->timestamps(false); di bawah id()
            // Tapi sebaiknya biarkan saja untuk tracking.
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