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
        Schema::create('perbaikans', function (Blueprint $table) { // Nama tabel 'perbaikans' (plural)
            $table->id('no_perbaikan'); // Primary key 'no_perbaikan'

            // Foreign key 'no_kerusakan'
            $table->unsignedBigInteger('no_kerusakan');
            $table->foreign('no_kerusakan')
                  ->references('no_kerusakan')
                  ->on('kerusakans') // Tabel referensi 'kerusakans'
                  ->onDelete('cascade'); // Hapus perbaikan jika kerusakan dihapus

            $table->date('tanggal_perbaikan');
            $table->string('status', 25);
            $table->string('kondisi_perbaikan', 5);
            $table->string('keterangan', 255)->nullable();
            $table->timestamps(); // Mungkin tidak perlu jika hanya mencatat transaksi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perbaikans');
    }
};