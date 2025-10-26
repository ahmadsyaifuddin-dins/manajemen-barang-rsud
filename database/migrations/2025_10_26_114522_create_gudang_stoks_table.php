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
        Schema::create('gudang_stoks', function (Blueprint $table) { // Nama tabel 'gudang_stoks' (plural)
            $table->id('no_gudang'); // Primary key 'no_gudang'

            // Foreign key 'no_barang_gudang'
            $table->unsignedBigInteger('no_barang_gudang');
            $table->foreign('no_barang_gudang')
                  ->references('no_barang_gudang')
                  ->on('barang_gudangs') // Tabel referensi 'barang_gudangs'
                  ->onDelete('cascade'); // Hapus stok jika barang gudang dihapus

            $table->integer('jumlah_barang')->default(0); // Jumlah barang, default 0
            $table->string('keterangan_gudang', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_stoks');
    }
};