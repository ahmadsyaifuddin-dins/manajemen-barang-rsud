<?php

namespace App\Http\Controllers;

use App\Exports\GudangKeluarExport;
use App\Exports\GudangMasukExport;
use App\Exports\GudangStokExport;
use App\Exports\InventarisExport;
use App\Exports\KerusakanExport;
use App\Exports\PerbaikanExport;
use Illuminate\Http\Request;
use App\Models\InventarisBarang;
use App\MoonShine\Resources\GudangKeluarResource;
use App\MoonShine\Resources\GudangMasukResource;
use App\MoonShine\Resources\GudangStokResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\MoonShine\Resources\InventarisBarangResource;
use App\MoonShine\Resources\KerusakanResource;
use App\MoonShine\Resources\PerbaikanResource;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function inventarisPdf(Request $request)
    {
        // 1. Buat instance dari Resource
        $resource = new InventarisBarangResource();

        // 2. Gunakan resolveQuery() dengan type hint
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $resource->resolveQuery();

        // 3. Ambil data yang SUDAH TERFILTER DAN TER-SORTING
        $filteredData = $query->get();

        // Siapkan data untuk view
        $tanggalCetak = Carbon::now('Asia/Makassar')->format('d-m-Y H:i:s');

        // Load view Blade untuk PDF
        $pdf = Pdf::loadView('reports.inventaris_pdf', [
            'inventarisData' => $filteredData,
            'tanggalCetak' => $tanggalCetak
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-inventaris-barang-' . $tanggalCetak . '.pdf');
    }

    public function stokGudangPdf(Request $request)
    {
        // 1. Buat instance dari Resource
        $resource = new GudangStokResource();

        // 2. Gunakan resolveQuery() (Otomatis filter & sort)
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $resource->resolveQuery();

        // 3. Ambil data yang SUDAH TERFILTER
        $filteredData = $query->get();
        
        // Siapkan data untuk view
        $tanggalCetak = Carbon::now('Asia/Makassar')->format('d-m-Y H:i:s');

        // 4. Load view PDF baru
        $pdf = Pdf::loadView('reports.stok_gudang_pdf', [
            'stokData' => $filteredData, // Kirim data ke view
            'tanggalCetak' => $tanggalCetak
        ]);

        // 5. Atur kertas (Portrait cukup)
        $pdf->setPaper('a4', 'portrait');

        // 6. Tampilkan di browser
        return $pdf->stream('laporan-stok-gudang-' . $tanggalCetak . '.pdf');
    }

    /**
     * TAMBAHKAN METHOD BARU INI UNTUK EXCEL INVENTARIS
     */
    public function inventarisExcel(Request $request)
    {
        // 1. Buat instance dari Resource
        $resource = new InventarisBarangResource();

        // 2. Gunakan resolveQuery() (Otomatis filter & sort)
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $resource->resolveQuery();

        // 3. Ambil data yang SUDAH TERFILTER
        $filteredData = $query->get();
        
        // Siapkan tanggal (untuk nama file)
        $tanggalCetak = Carbon::now('Asia/Makassar')->format('d-m-Y');

        // 4. Download menggunakan class Export yang baru kita buat
        return Excel::download(
            new InventarisExport($filteredData), 
            'laporan-inventaris-barang-' . $tanggalCetak . '.xlsx'
        );
    }

    public function stokGudangExcel(Request $request)
    {
        // 1. Buat instance dari Resource
        $resource = new GudangStokResource();

        // 2. Gunakan resolveQuery() (Otomatis filter & sort)
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $resource->resolveQuery();

        // 3. Ambil data yang SUDAH TERFILTER
        $filteredData = $query->get();
        
        // Siapkan tanggal (untuk nama file)
        $tanggalCetak = Carbon::now('Asia/Makassar')->format('d-m-Y');

        // 4. Download menggunakan class Export yang baru
        return Excel::download(
            new GudangStokExport($filteredData), 
            'laporan-stok-gudang-' . $tanggalCetak . '.xlsx'
        );
    }

    public function barangMasukPdf(Request $request)
    {
        $resource = new GudangMasukResource();
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $resource->resolveQuery();
        $filteredData = $query->get();
        
        $tanggalCetak = Carbon::now('Asia/Makassar')->format('d-m-Y H:i:s');

        $pdf = Pdf::loadView('reports.barang_masuk_pdf', [
            'masukData' => $filteredData,
            'tanggalCetak' => $tanggalCetak
        ]);

        $pdf->setPaper('a4', 'landscape'); // Landscape agar lebih muat
        return $pdf->stream('laporan-barang-masuk-' . $tanggalCetak . '.pdf');
    }

    /**
     * 4. METHOD UNTUK BARANG MASUK EXCEL
     */
    public function barangMasukExcel(Request $request)
    {
        $resource = new GudangMasukResource();
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $resource->resolveQuery();
        $filteredData = $query->get();
        
        $tanggalCetak = Carbon::now('Asia/Makassar')->format('d-m-Y');

        return Excel::download(
            new GudangMasukExport($filteredData), 
            'laporan-barang-masuk-' . $tanggalCetak . '.xlsx'
        );
    }

    public function barangKeluarPdf(Request $request)
    {
        $resource = new GudangKeluarResource();
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $resource->resolveQuery();
        $filteredData = $query->get();
        
        $tanggalCetak = Carbon::now('Asia/Makassar')->format('d-m-Y H:i:s');

        $pdf = Pdf::loadView('reports.barang_keluar_pdf', [
            'keluarData' => $filteredData,
            'tanggalCetak' => $tanggalCetak
        ]);

        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('laporan-barang-keluar-' . $tanggalCetak . '.pdf');
    }

    /**
     * 4. BARANG KELUAR EXCEL
     */
    public function barangKeluarExcel(Request $request)
    {
        $resource = new GudangKeluarResource();
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $resource->resolveQuery();
        $filteredData = $query->get();
        
        $tanggalCetak = Carbon::now('Asia/Makassar')->format('d-m-Y');

        return Excel::download(
            new GudangKeluarExport($filteredData), 
            'laporan-barang-keluar-' . $tanggalCetak . '.xlsx'
        );
    }

    public function kerusakanPdf(Request $request)
    {
        $resource = new KerusakanResource();
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $resource->resolveQuery();
        $filteredData = $query->get();
        
        $tanggalCetak = Carbon::now('Asia/Makassar')->format('d-m-Y H:i:s');

        $pdf = Pdf::loadView('reports.kerusakan_pdf', [
            'kerusakanData' => $filteredData,
            'tanggalCetak' => $tanggalCetak
        ]);

        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('laporan-kerusakan-' . $tanggalCetak . '.pdf');
    }

    /**
     * 4. UNTUK KERUSAKAN EXCEL
     */
    public function kerusakanExcel(Request $request)
    {
        $resource = new KerusakanResource();
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $resource->resolveQuery();
        $filteredData = $query->get();
        
        $tanggalCetak = Carbon::now('Asia/Makassar')->format('d-m-Y');

        return Excel::download(
            new KerusakanExport($filteredData), 
            'laporan-kerusakan-' . $tanggalCetak . '.xlsx'
        );
    }

    public function perbaikanPdf(Request $request)
    {
        $resource = new PerbaikanResource();
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $resource->resolveQuery();
        $filteredData = $query->get();
        
        $tanggalCetak = Carbon::now('Asia/Makassar')->format('d-m-Y H:i:s');

        $pdf = Pdf::loadView('reports.perbaikan_pdf', [
            'perbaikanData' => $filteredData,
            'tanggalCetak' => $tanggalCetak
        ]);

        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('laporan-perbaikan-' . $tanggalCetak . '.pdf');
    }

    /**
     * 4. TAMBAHKAN METHOD BARU UNTUK PERBAIKAN EXCEL
     */
    public function perbaikanExcel(Request $request)
    {
        $resource = new PerbaikanResource();
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $resource->resolveQuery();
        $filteredData = $query->get();
        
        $tanggalCetak = Carbon::now('Asia/Makassar')->format('d-m-Y');

        return Excel::download(
            new PerbaikanExport($filteredData), 
            'laporan-perbaikan-' . $tanggalCetak . '.xlsx'
        );
    }
}
