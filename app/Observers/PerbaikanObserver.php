<?php

namespace App\Observers;

use App\Models\Perbaikan;
use App\Models\Kerusakan; // Import Kerusakan
use App\Models\InventarisBarang; // Import InventarisBarang

class PerbaikanObserver
{
    /**
     * Handle the Perbaikan "created" event.
     * Update status kerusakan & kondisi inventaris saat perbaikan dibuat.
     */
    public function created(Perbaikan $perbaikan): void
    {
        $this->updateRelatedStatus($perbaikan);
    }

    /**
     * Handle the Perbaikan "updated" event.
     * Update status kerusakan & kondisi inventaris saat perbaikan diubah.
     */
    public function updated(Perbaikan $perbaikan): void
    {
        // Hanya update jika status atau kondisi perbaikan berubah
        if ($perbaikan->isDirty('status') || $perbaikan->isDirty('kondisi_perbaikan')) {
             $this->updateRelatedStatus($perbaikan);
        }
    }

    /**
     * Helper function to update related models.
     */
    protected function updateRelatedStatus(Perbaikan $perbaikan): void
    {
        $kerusakan = Kerusakan::find($perbaikan->no_kerusakan);
        if ($kerusakan) {
            // Update status di tabel Kerusakan
            $kerusakan->status_kerusakan = $perbaikan->status;
            $kerusakan->saveQuietly(); // saveQuietly agar tidak trigger event lain jika Kerusakan punya observer

            // Update kondisi di tabel InventarisBarang (melalui relasi Kerusakan)
            $inventaris = InventarisBarang::find($kerusakan->no_inventaris);
            if ($inventaris) {
                // Hanya update jika kondisi perbaikan BUKAN 'Belum'
                if ($perbaikan->kondisi_perbaikan !== 'Belum') {
                     $inventaris->kondisi = $perbaikan->kondisi_perbaikan;
                     $inventaris->saveQuietly();
                }
            }
        }
    }

    /**
     * Handle the Perbaikan "deleted" event.
     */
    public function deleted(Perbaikan $perbaikan): void
    {
        // Opsional: Logika jika data perbaikan dihapus
        // Misalnya, reset status kerusakan ke 'Belum diperbaiki'?
        // $kerusakan = Kerusakan::find($perbaikan->no_kerusakan);
        // if ($kerusakan) {
        //     $kerusakan->status_kerusakan = 'Belum diperbaiki';
        //     $kerusakan->saveQuietly();
             // Reset kondisi inventaris mungkin tidak diperlukan/diinginkan
        // }
    }

    // ... method restored() dan forceDeleted() jika pakai SoftDeletes
}