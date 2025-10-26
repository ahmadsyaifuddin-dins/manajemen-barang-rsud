<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import

class Rusak extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_rusak'; // Primary key

    /**
     * Mendapatkan data perbaikan yang dinyatakan rusak berat.
     */
    public function perbaikan(): BelongsTo
    {
        return $this->belongsTo(Perbaikan::class, 'no_perbaikan', 'no_perbaikan');
    }
}