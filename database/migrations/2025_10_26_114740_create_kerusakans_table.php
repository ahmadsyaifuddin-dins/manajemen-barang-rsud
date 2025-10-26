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
        Schema::create('kerusakans', function (Blueprint $table) { // Nama tabel 'kerusakans' (plural)
            $table->id('no_kerusakan'); // Primary key 'no_kerusakan'
            $table->date('tanggal');

            // Foreign key 'no_inventaris'
            $table->unsignedBigInteger('no_inventaris');
            $table->foreign('no_inventaris')
                  ->references('no_inventaris')
                  ->on('inventaris_barangs') // Tabel referensi 'inventaris_barangs'
                  ->onDelete('cascade'); // Hapus kerusakan jika inventaris dihapus

            $table->string('kerusakan', 150);
            $table->string('status_kerusakan', 25);
            $table->string('keterangan_kerusakan', 255)->nullable();
            $table->timestamps(); // Mungkin tidak perlu jika hanya mencatat transaksi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kerusakans');
    }
};