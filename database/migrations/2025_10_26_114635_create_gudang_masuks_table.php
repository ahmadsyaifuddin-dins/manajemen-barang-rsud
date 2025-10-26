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
        Schema::create('gudang_masuks', function (Blueprint $table) { // Nama tabel 'gudang_masuks' (plural)
            $table->id('no_gudang_masuk'); // Primary key 'no_gudang_masuk'

            // Foreign key 'no_gudang' (merujuk ke gudang_stoks)
            $table->unsignedBigInteger('no_gudang');
            $table->foreign('no_gudang')
                  ->references('no_gudang')
                  ->on('gudang_stoks') // Tabel referensi 'gudang_stoks'
                  ->onDelete('cascade'); // Hapus record masuk jika stok dihapus

            $table->date('tanggal_masuk');
            $table->integer('jumlah_masuk');
            $table->timestamps(); // Mungkin tidak perlu jika hanya mencatat transaksi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_masuks');
    }
};