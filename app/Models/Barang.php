<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import HasMany

class Barang extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_barang'; // Tentukan primary key jika bukan 'id'

    /**
     * Mendapatkan semua inventaris untuk barang ini.
     */
    public function inventarisBarangs(): HasMany
    {
        return $this->hasMany(InventarisBarang::class, 'no_barang', 'no_barang');
    }
}