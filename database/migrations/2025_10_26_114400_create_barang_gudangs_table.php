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
        Schema::create('barang_gudangs', function (Blueprint $table) { // Nama tabel 'barang_gudangs' (plural)
            $table->id('no_barang_gudang'); // Primary key 'no_barang_gudang'
            $table->string('nama_barang_gudang', 255);
            $table->string('jenis_barang_gudang', 30);
            $table->string('kategori_barang_gudang', 30);
            $table->timestamps(); // Biarkan jika perlu created_at/updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_gudangs');
    }
};