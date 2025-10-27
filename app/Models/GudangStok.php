<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import
use Illuminate\Database\Eloquent\Relations\HasMany;   // Import

class GudangStok extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_gudang'; // Primary key
    
    protected $fillable = [
        'no_barang_gudang',
        'jumlah_barang',
        'keterangan_gudang',
    ];

    /**
     * Mendapatkan barang gudang yang terkait dengan stok ini.
     */
    public function barangGudang(): BelongsTo
    {
        return $this->belongsTo(BarangGudang::class, 'no_barang_gudang', 'no_barang_gudang');
    }
    

    /**
     * Mendapatkan semua record barang masuk untuk stok ini.
     */
    public function gudangMasuks(): HasMany
    {
        return $this->hasMany(GudangMasuk::class, 'no_gudang', 'no_gudang');
    }

    /**
     * Mendapatkan semua record barang keluar untuk stok ini.
     */
    public function gudangKeluars(): HasMany
    {
        return $this->hasMany(GudangKeluar::class, 'no_gudang', 'no_gudang');
    }
}