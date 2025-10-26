<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import
use Illuminate\Database\Eloquent\Relations\HasOne;    // Import

class Kerusakan extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_kerusakan'; // Primary key

    /**
     * Mendapatkan data inventaris yang rusak.
     */
    public function inventarisBarang(): BelongsTo
    {
        return $this->belongsTo(InventarisBarang::class, 'no_inventaris', 'no_inventaris');
    }

    /**
     * Mendapatkan data perbaikan untuk kerusakan ini (jika ada).
     */
    public function perbaikan(): HasOne
    {
        return $this->hasOne(Perbaikan::class, 'no_kerusakan', 'no_kerusakan');
    }
}