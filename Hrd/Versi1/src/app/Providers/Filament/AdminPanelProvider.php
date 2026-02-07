<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Auth\CustomLogin;
use Livewire\Livewire;
use Illuminate\Support\HtmlString;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(CustomLogin::class)
            ->colors([
                'primary' => '#0D9488', // Teal Medical
            ])
            ->brandLogo(asset('images/3K.png'))
            ->renderHook(
            'panels::head.end',
            fn () => new HtmlString("
                <style>
                    /* Menargetkan gambar logo di header halaman login */
                    .fi-simple-header img {
                        border-radius: 50% !important; /* Membuat sudut jadi bulat */
                        width: 100px !important;       /* Lebar paksa */
                        height: 100px !important;      /* Tinggi paksa (harus sama dengan lebar biar bulat) */
                        object-fit: cover !important;  /* Agar gambar tidak gepeng kalau aslinya bukan persegi */
                        
                        /* Opsional: Tambahkan border tipis biar makin tegas */
                        border: 3px solid #0D9488 !important; 
                        padding: 2px !important;
                        background-color: white !important;
                    }
                </style>
            ")
        )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
    public function boot(): void
    {
        // Daftarkan komponen secara manual agar terbaca oleh Livewire
        Livewire::component('app.filament.auth.custom-login', CustomLogin::class);
    }
}
