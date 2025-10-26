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
        Schema::create('gudang_keluars', function (Blueprint $table) { // Nama tabel 'gudang_keluars' (plural)
            $table->id('no_gudang_keluar'); // Primary key 'no_gudang_keluar'

            // Foreign key 'no_gudang' (merujuk ke gudang_stoks)
            $table->unsignedBigInteger('no_gudang');
            $table->foreign('no_gudang')
                  ->references('no_gudang')
                  ->on('gudang_stoks') // Tabel referensi 'gudang_stoks'
                  ->onDelete('cascade'); // Hapus record keluar jika stok dihapus

             // Foreign key 'no_ruangan'
            $table->unsignedBigInteger('no_ruangan');
            $table->foreign('no_ruangan')
                  ->references('no_ruangan')
                  ->on('ruangans') // Tabel referensi 'ruangans'
                  ->onDelete('restrict'); // Jangan hapus jika ruangan masih ada (atau sesuaikan)

            $table->date('tanggal_keluar');
            $table->integer('jumlah_keluar');
            $table->string('keterangan_keluar', 255)->nullable();
            $table->timestamps(); // Mungkin tidak perlu jika hanya mencatat transaksi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_keluars');
    }
};