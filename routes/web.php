<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/report/inventaris/pdf', [ReportController::class, 'inventarisPdf'])->name('report.inventaris.pdf');
Route::get('/report/gudang/stok/pdf', [ReportController::class, 'stokGudangPdf'])->name('report.gudang.stok.pdf');
Route::get('/report/gudang/masuk/pdf', [ReportController::class, 'barangMasukPdf'])->name('report.gudang.masuk.pdf');
Route::get('/report/gudang/keluar/pdf', [ReportController::class, 'barangKeluarPdf'])->name('report.gudang.keluar.pdf');
Route::get('/report/kerusakan/pdf', [ReportController::class, 'kerusakanPdf'])->name('report.kerusakan.pdf');
Route::get('/report/perbaikan/pdf', [ReportController::class, 'perbaikanPdf'])->name('report.perbaikan.pdf'); // <-- TAMBAH INI

Route::get('/report/inventaris/excel', [ReportController::class, 'inventarisExcel'])->name('report.inventaris.excel');
Route::get('/report/gudang/stok/excel', [ReportController::class, 'stokGudangExcel'])->name('report.gudang.stok.excel');
Route::get('/report/gudang/masuk/excel', [ReportController::class, 'barangMasukExcel'])->name('report.gudang.masuk.excel');
Route::get('/report/gudang/keluar/excel', [ReportController::class, 'barangKeluarExcel'])->name('report.gudang.keluar.excel');
Route::get('/report/kerusakan/excel', [ReportController::class, 'kerusakanExcel'])->name('report.kerusakan.excel');
Route::get('/report/perbaikan/excel', [ReportController::class, 'perbaikanExcel'])->name('report.perbaikan.excel'); // <-- TAMBAH INI
