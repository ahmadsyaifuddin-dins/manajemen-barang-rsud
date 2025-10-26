<?php

namespace App\Observers;

use App\Models\GudangMasuk;
use App\Models\GudangStok; // Import GudangStok

class GudangMasukObserver
{
    /**
     * Handle the GudangMasuk "created" event.
     * Tambah stok saat data barang masuk baru dibuat.
     */
    public function created(GudangMasuk $gudangMasuk): void
    {
        $stok = GudangStok::find($gudangMasuk->no_gudang);
        if ($stok) {
            $stok->increment('jumlah_barang', $gudangMasuk->jumlah_masuk);
            // $stok->jumlah_barang += $gudangMasuk->jumlah_masuk;
            // $stok->save();
        }
    }

    /**
     * Handle the GudangMasuk "updated" event.
     * Sesuaikan stok berdasarkan perubahan jumlah masuk.
     */
    public function updated(GudangMasuk $gudangMasuk): void
    {
        // Cek apakah jumlah_masuk berubah
        if ($gudangMasuk->isDirty('jumlah_masuk')) {
            $stok = GudangStok::find($gudangMasuk->no_gudang);
            if ($stok) {
                $jumlahLama = $gudangMasuk->getOriginal('jumlah_masuk'); // Ambil nilai lama
                $jumlahBaru = $gudangMasuk->jumlah_masuk;
                $selisih = $jumlahBaru - $jumlahLama;

                // Update stok berdasarkan selisih
                $stok->increment('jumlah_barang', $selisih);
                // $stok->jumlah_barang += $selisih;
                // $stok->save();
            }
        }
         // Handle jika no_gudang (relasi ke stok) berubah (lebih kompleks, opsional)
         if ($gudangMasuk->isDirty('no_gudang')) {
             // Kurangi stok lama
             $stokLama = GudangStok::find($gudangMasuk->getOriginal('no_gudang'));
             if($stokLama) {
                $stokLama->decrement('jumlah_barang', $gudangMasuk->jumlah_masuk);
             }
             // Tambah stok baru
             $stokBaru = GudangStok::find($gudangMasuk->no_gudang);
             if($stokBaru) {
                $stokBaru->increment('jumlah_barang', $gudangMasuk->jumlah_masuk);
             }
         }
    }

    /**
     * Handle the GudangMasuk "deleted" event.
     * Kurangi stok saat data barang masuk dihapus.
     */
    public function deleted(GudangMasuk $gudangMasuk): void
    {
        $stok = GudangStok::find($gudangMasuk->no_gudang);
        if ($stok) {
            // Pastikan stok tidak menjadi negatif
            $stok->decrement('jumlah_barang', $gudangMasuk->jumlah_masuk);
            // $stok->jumlah_barang -= $gudangMasuk->jumlah_masuk;
            // $stok->save();
        }
    }

    /**
     * Handle the GudangMasuk "restored" event.
     */
    public function restored(GudangMasuk $gudangMasuk): void
    {
        // Jika menggunakan SoftDeletes, tambahkan logika penambahan stok kembali
    }

    /**
     * Handle the GudangMasuk "force deleted" event.
     */
    public function forceDeleted(GudangMasuk $gudangMasuk): void
    {
        // Jika menggunakan SoftDeletes
    }
}