<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import

class Serah extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_serah'; // Primary key

    protected $fillable = [
        'no_perbaikan',
        'no_inventaris',
        'tanggal',
        'status',
        'keterangan',
    ];
    /**
     * Mendapatkan data perbaikan yang diserahterimakan.
     */
    public function perbaikan(): BelongsTo
    {
        return $this->belongsTo(Perbaikan::class, 'no_perbaikan', 'no_perbaikan');
    }
}