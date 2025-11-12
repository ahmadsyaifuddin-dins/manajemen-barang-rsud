<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import

class GudangKeluar extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_gudang_keluar'; // Primary key

    protected $fillable = [
        'no_gudang_keluar',
        'no_gudang',
        'no_ruangan',
        'tanggal_keluar',
        'jumlah_keluar',
        'keterangan_keluar',        
    ];
    /**
     * Mendapatkan data stok yang terkait dengan record keluar ini.
     */
    public function gudangStok(): BelongsTo
    {
        return $this->belongsTo(GudangStok::class, 'no_gudang', 'no_gudang');
    }

    /**
     * Mendapatkan ruangan tujuan barang keluar.
     */
    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'no_ruangan', 'no_ruangan');
    }
}