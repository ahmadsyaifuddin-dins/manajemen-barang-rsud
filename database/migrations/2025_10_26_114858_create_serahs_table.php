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
        Schema::create('serahs', function (Blueprint $table) { // Nama tabel 'serahs' (plural)
            $table->id('no_serah'); // Primary key 'no_serah'

            // Foreign key 'no_perbaikan'
            $table->unsignedBigInteger('no_perbaikan');
            $table->foreign('no_perbaikan')
                  ->references('no_perbaikan')
                  ->on('perbaikans') // Tabel referensi 'perbaikans'
                  ->onDelete('cascade'); // Hapus serah jika perbaikan dihapus

            $table->date('tanggal_serah');
            $table->string('keterangan_serah', 255)->nullable();
            $table->timestamps(); // Mungkin tidak perlu jika hanya mencatat transaksi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serahs');
    }
};