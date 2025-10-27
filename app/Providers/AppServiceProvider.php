<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\GudangMasuk;
use App\Observers\GudangMasukObserver;
use App\Models\GudangKeluar;
use App\Observers\GudangKeluarObserver;
use App\Models\Perbaikan;
use App\Observers\PerbaikanObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Daftarkan Observer di sini
        GudangMasuk::observe(GudangMasukObserver::class);
        GudangKeluar::observe(GudangKeluarObserver::class);
        Perbaikan::observe(PerbaikanObserver::class);
    }
}
