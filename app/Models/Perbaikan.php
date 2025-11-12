<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import
use Illuminate\Database\Eloquent\Relations\HasOne;    // Import

class Perbaikan extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_perbaikan'; // Primary key

    protected $fillable = [
        'no_perbaikan',
        'no_kerusakan',
        'tanggal',
        'kerusakan',
        'status_perbaikan',
        'keterangan_perbaikan',
    ];
    /**
     * Mendapatkan data kerusakan yang diperbaiki.
     */
    public function kerusakan(): BelongsTo
    {
        return $this->belongsTo(Kerusakan::class, 'no_kerusakan', 'no_kerusakan');
    }

    /**
     * Mendapatkan data serah terima untuk perbaikan ini (jika ada).
     */
    public function serah(): HasOne
    {
        return $this->hasOne(Serah::class, 'no_perbaikan', 'no_perbaikan');
    }

     /**
     * Mendapatkan data rusak berat untuk perbaikan ini (jika ada).
     */
    public function rusak(): HasOne
    {
        return $this->hasOne(Rusak::class, 'no_perbaikan', 'no_perbaikan');
    }
}