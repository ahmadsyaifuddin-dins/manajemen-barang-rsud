<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/report/inventaris/pdf', [ReportController::class, 'inventarisPdf'])->name('report.inventaris.pdf');
Route::get('/report/gudang/stok/pdf', [ReportController::class, 'stokGudangPdf'])->name('report.gudang.stok.pdf');
Route::get('/report/gudang/masuk/pdf', [ReportController::class, 'barangMasukPdf'])->name('report.gudang.masuk.pdf');

Route::get('/report/inventaris/excel', [ReportController::class, 'inventarisExcel'])->name('report.inventaris.excel');
Route::get('/report/gudang/stok/excel', [ReportController::class, 'stokGudangExcel'])->name('report.gudang.stok.excel');
Route::get('/report/gudang/masuk/excel', [ReportController::class, 'barangMasukExcel'])->name('report.gudang.masuk.excel');