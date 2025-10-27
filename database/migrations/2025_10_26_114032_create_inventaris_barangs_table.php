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
        // Menggunakan nama tabel 'inventaris_barangs' (plural)
        Schema::create('inventaris_barangs', function (Blueprint $table) {
            // Primary key 'no_inventaris' auto-increment
            $table->id('no_inventaris');

            // Foreign key 'no_ruangan'
            $table->unsignedBigInteger('no_ruangan');
            $table->foreign('no_ruangan')
                  ->references('no_ruangan') // Merujuk ke kolom 'no_ruangan' di tabel 'ruangans'
                  ->on('ruangans')          // Nama tabel 'ruangans' (plural)
                  ->onDelete('cascade'); // Opsional: Hapus inventaris jika ruangan dihapus

            // Foreign key 'no_barang'
            $table->unsignedBigInteger('no_barang');
            $table->foreign('no_barang')
                  ->references('no_barang') // Merujuk ke kolom 'no_barang' di tabel 'barangs'
                  ->on('barangs')           // Nama tabel 'barangs' (plural)
                  ->onDelete('cascade');  // Opsional: Hapus inventaris jika barang dihapus

            // Kolom lain dari SQL
            $table->char('kode_barang', 25)->nullable(); // Kode barang 25 karakter dan bisa null
            $table->date('tanggal_masuk');
            $table->string('kondisi', 5); // Sesuaikan panjang jika perlu
            $table->string('keterangan_inventaris', 50)->nullable();

            // Timestamps (created_at, updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris_barangs');
    }
};