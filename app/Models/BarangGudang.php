<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BarangGudang extends Model
{
    use HasFactory;

    protected $table = 'barang_gudangs'; // Nama tabel
    protected $primaryKey = 'no_barang_gudang'; // Primary key
    public $incrementing = false; // Jika PK bukan auto increment
    protected $keyType = 'string'; // Jika PK bertipe string

    // Tambahkan fillable untuk mass assignment
    protected $fillable = [
        'no_barang_gudang',
        'nama_barang_gudang',
        'jenis_barang_gudang',
        'kategori_barang_gudang',
    ];

    /**
     * Relasi: BarangGudang memiliki banyak GudangStok
     */
    public function gudangStoks(): HasMany
    {
        return $this->hasMany(GudangStok::class, 'no_barang_gudang', 'no_barang_gudang');
    }
}