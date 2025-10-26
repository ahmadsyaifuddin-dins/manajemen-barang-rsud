<?php

namespace App\Observers;

use App\Models\GudangKeluar;
use App\Models\GudangStok; // Import GudangStok

class GudangKeluarObserver
{
    /**
     * Handle the GudangKeluar "created" event.
     * Kurangi stok saat data barang keluar baru dibuat.
     */
    public function created(GudangKeluar $gudangKeluar): void
    {
        $stok = GudangStok::find($gudangKeluar->no_gudang);
        if ($stok) {
            // Pastikan stok cukup sebelum mengurangi (validasi bisa ditambahkan di rules Resource/Request)
            $stok->decrement('jumlah_barang', $gudangKeluar->jumlah_keluar);
        }
    }

    /**
     * Handle the GudangKeluar "updated" event.
     * Sesuaikan stok berdasarkan perubahan jumlah keluar.
     */
    public function updated(GudangKeluar $gudangKeluar): void
    {
         // Cek apakah jumlah_keluar berubah
        if ($gudangKeluar->isDirty('jumlah_keluar')) {
            $stok = GudangStok::find($gudangKeluar->no_gudang);
            if ($stok) {
                $jumlahLama = $gudangKeluar->getOriginal('jumlah_keluar');
                $jumlahBaru = $gudangKeluar->jumlah_keluar;
                $selisih = $jumlahBaru - $jumlahLama; // Jika baru > lama, selisih positif (mengurangi stok lebih banyak)

                // Update stok berdasarkan selisih (berkebalikan dari barang masuk)
                $stok->decrement('jumlah_barang', $selisih);
            }
        }
         // Handle jika no_gudang (relasi ke stok) berubah (lebih kompleks, opsional)
         if ($gudangKeluar->isDirty('no_gudang')) {
             // Tambah stok lama
             $stokLama = GudangStok::find($gudangKeluar->getOriginal('no_gudang'));
             if($stokLama) {
                 $stokLama->increment('jumlah_barang', $gudangKeluar->jumlah_keluar);
             }
             // Kurangi stok baru
             $stokBaru = GudangStok::find($gudangKeluar->no_gudang);
             if($stokBaru) {
                 $stokBaru->decrement('jumlah_barang', $gudangKeluar->jumlah_keluar);
             }
         }
    }

    /**
     * Handle the GudangKeluar "deleted" event.
     * Tambah stok kembali saat data barang keluar dihapus.
     */
    public function deleted(GudangKeluar $gudangKeluar): void
    {
        $stok = GudangStok::find($gudangKeluar->no_gudang);
        if ($stok) {
            $stok->increment('jumlah_barang', $gudangKeluar->jumlah_keluar);
        }
    }

    /**
     * Handle the GudangKeluar "restored" event.
     */
    public function restored(GudangKeluar $gudangKeluar): void
    {
        // Jika pakai SoftDeletes, kurangi stok kembali
    }

    /**
     * Handle the GudangKeluar "force deleted" event.
     */
    public function forceDeleted(GudangKeluar $gudangKeluar): void
    {
        // Jika pakai SoftDeletes
    }
}