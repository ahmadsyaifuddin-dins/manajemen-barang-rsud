<?php

declare(strict_types=1);

namespace App\Providers;

use App\MoonShine\Resources\BarangGudangResource;
use App\MoonShine\Resources\BarangResource;
use App\MoonShine\Resources\GudangKeluarResource;
use App\MoonShine\Resources\GudangMasukResource;
use App\MoonShine\Resources\GudangStokResource;
use App\MoonShine\Resources\InventarisBarangResource;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\RuanganResource;
use App\MoonShine\Resources\UserResource;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    /**
     * Daftarkan resource aplikasi Anda di sini
     * @return array
     */
    protected function resources(): array
    {
        return [
            new RuanganResource(),
            new BarangResource(),
            new BarangGudangResource(),
            new UserResource(),
            new InventarisBarangResource(),
            new GudangStokResource(),
            new GudangMasukResource(),
            new GudangKeluarResource(),
            // ... resource lain
        ];
    }

    /**
     * Daftarkan halaman kustom Anda di sini (jika ada)
     * @return array
     */
    protected function pages(): array
    {
        return [];
    }

    /**
     * Konfigurasi menu utama Moonshine
     * @return array
     */
    protected function menu(): array
    {
        return [
            // Grup menu default untuk user/role Moonshine
            MenuGroup::make(static fn() => __('moonshine::ui.resource.system'), [
                MenuItem::make(
                    static fn() => __('moonshine::ui.resource.admins_title'),
                    new MoonShineUserResource()
                ),
                MenuItem::make(
                    static fn() => __('moonshine::ui.resource.role_title'),
                    new MoonShineUserRoleResource()
                ),
            ]), // ->icon('...')

            // Grup Menu Master Data
            MenuGroup::make('Master Data', [
                MenuItem::make(
                    'Ruangan',
                    new RuanganResource()
                )->icon('heroicons.building-office'),

                MenuItem::make(
                    'Barang',
                    new BarangResource()
                )->icon('heroicons.outline.cube'),

                MenuItem::make(
                    'Barang Gudang',
                    new BarangGudangResource()
                )->icon('heroicons.outline.archive-box'),

                MenuItem::make(
                    'User Aplikasi',
                    new UserResource()
                )->icon('heroicons.outline.users'),

            ])->icon('heroicons.outline.building-office-2'),

            MenuItem::make(
                'Inventaris Barang',
                new InventarisBarangResource()
            )->icon('heroicons.outline.clipboard-document-list'),

            MenuGroup::make('Gudang', [
                MenuItem::make(
                    'Stok Gudang',
                    new GudangStokResource()
                )->icon('heroicons.outline.circle-stack'),

                MenuItem::make(
                    'Barang Masuk',
                    new GudangMasukResource()
                )->icon('heroicons.outline.arrow-down-tray'),

                MenuItem::make(
                    'Barang Keluar',
                    new GudangKeluarResource() // <-- Instansiasi langsung
                )->icon('heroicons.outline.arrow-up-tray'), // <-- Icon opsional
            ])->icon('heroicons.outline.archive-box-arrow-down'),

            MenuItem::make('Documentation', 'https://moonshine-laravel.com/docs/2.x')
                ->badge(fn() => 'Check'),


        ];
    }

    /**
     * Konfigurasi tema (opsional)
     * @return array{css: string, colors: array, darkColors: array}
     */
    protected function theme(): array
    {
        return [];
    }
}
