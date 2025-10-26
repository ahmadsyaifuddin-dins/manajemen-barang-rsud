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
        Schema::create('rusaks', function (Blueprint $table) { // Nama tabel 'rusaks' (plural)
            $table->id('no_rusak'); // Primary key 'no_rusak'

            // Foreign key 'no_perbaikan'
            $table->unsignedBigInteger('no_perbaikan');
            $table->foreign('no_perbaikan')
                  ->references('no_perbaikan')
                  ->on('perbaikans') // Tabel referensi 'perbaikans'
                  ->onDelete('cascade'); // Hapus rusak jika perbaikan dihapus

            $table->date('tanggal_rusak');
            $table->string('keterangan_rusak', 255)->nullable();
            $table->timestamps(); // Mungkin tidak perlu jika hanya mencatat transaksi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rusaks');
    }
};