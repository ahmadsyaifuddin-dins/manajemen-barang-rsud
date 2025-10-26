<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo
use Illuminate\Database\Eloquent\Relations\HasMany;   // Import HasMany

class InventarisBarang extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_inventaris'; // Tentukan primary key

    /**
     * Mendapatkan barang yang terkait dengan inventaris ini.
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'no_barang', 'no_barang');
    }

    /**
     * Mendapatkan ruangan tempat inventaris ini berada.
     */
    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'no_ruangan', 'no_ruangan');
    }

    /**
     * Mendapatkan semua data kerusakan untuk inventaris ini.
     */
    public function kerusakans(): HasMany
    {
        return $this->hasMany(Kerusakan::class, 'no_inventaris', 'no_inventaris');
    }
}