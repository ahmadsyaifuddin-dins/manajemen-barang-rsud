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
        Schema::create('users', function (Blueprint $table) { // Nama tabel 'users' (plural)
            $table->id('no_user'); // Primary key 'no_user'
            $table->string('nama_user', 255);
            $table->string('email')->unique();
            $table->string('password');

            // Foreign key 'no_ruangan'
            $table->unsignedBigInteger('no_ruangan');
            $table->foreign('no_ruangan')
                  ->references('no_ruangan')
                  ->on('ruangans') // Tabel referensi 'ruangans'
                  ->onDelete('restrict'); // Mencegah user dihapus jika ruangan masih ada (atau sesuaikan)

            $table->string('id_user', 25)->unique(); // Kolom username, harus unik
            $table->string('role_user', 20)->nullable(); // Role bisa kosong
            $table->rememberToken(); // Jika ingin fitur "remember me" Laravel
            $table->timestamps(); // Biarkan jika perlu created_at/updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};