<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import HasMany

class BarangGudang extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_barang_gudang'; // Tentukan primary key

    /**
     * Mendapatkan semua data stok untuk barang gudang ini.
     */
    public function gudangStoks(): HasMany
    {
        return $this->hasMany(GudangStok::class, 'no_barang_gudang', 'no_barang_gudang');
    }
}