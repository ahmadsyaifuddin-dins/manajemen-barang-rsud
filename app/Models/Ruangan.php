<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import HasMany

class Ruangan extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_ruangan'; // Tentukan primary key

    protected $fillable = [
        'no_ruangan',
        'nama_ruangan',
    ];
    /**
     * Mendapatkan semua inventaris barang di ruangan ini.
     */
    public function inventarisBarangs(): HasMany
    {
        return $this->hasMany(InventarisBarang::class, 'no_ruangan', 'no_ruangan');
    }

    /**
     * Mendapatkan semua user di ruangan ini.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'no_ruangan', 'no_ruangan');
    }

    /**
     * Mendapatkan semua record barang keluar untuk ruangan ini.
     */
    public function gudangKeluars(): HasMany
    {
        return $this->hasMany(GudangKeluar::class, 'no_ruangan', 'no_ruangan');
    }
}