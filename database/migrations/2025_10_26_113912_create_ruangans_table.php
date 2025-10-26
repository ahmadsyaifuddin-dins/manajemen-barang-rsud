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
        Schema::create('ruangans', function (Blueprint $table) { // Nama tabel jadi 'ruangans' (plural)
            $table->id('no_ruangan'); // Menggunakan 'no_ruangan' sebagai primary key auto-increment
            $table->string('nama_ruangan', 25);
            $table->string('kepala_ruangan', 50);
            $table->timestamps(); // Biarkan jika perlu created_at/updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangans');
    }
};