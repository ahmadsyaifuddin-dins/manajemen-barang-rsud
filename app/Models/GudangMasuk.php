<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import

class GudangMasuk extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_gudang_masuk'; // Primary key

    /**
     * Mendapatkan data stok yang terkait dengan record masuk ini.
     */
    public function gudangStok(): BelongsTo
    {
        return $this->belongsTo(GudangStok::class, 'no_gudang', 'no_gudang');
    }
}