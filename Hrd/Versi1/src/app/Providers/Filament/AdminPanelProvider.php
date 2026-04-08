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
            ->registration()
            ->colors([
                'primary' => Color::hex('#06B6D4'),
                'info'    => Color::hex('#22d3ee'),
                'success' => Color::hex('#5eead4'),
                'warning' => Color::hex('#f59e0b'),
                'danger'  => Color::hex('#f43f5e'),
            ])
            ->brandName('HRIS Klinik')
            ->brandLogo(asset('images/3K.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('images/3K.png'))
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                'Karyawan',
                'Kehadiran',
                'Penggajian',
                'Pengaturan',
            ])

            // ── CSS Theme via renderHook — tidak butuh Vite ──────
            ->renderHook('panels::head.end', fn () => new HtmlString('
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
/* =====================================================
   HRIS Theme — Mint | Ice Blue | Aquamarine | Cyan
   ===================================================== */
:root {
    --hc-bg:      #f0fafa;
    --hc-100:     #e0f5f2;
    --hc-200:     #ccf5f0;
    --hc-border:  #c8eee9;
    --hc-aqua:    #5eead4;
    --hc-cyan-lt: #22d3ee;
    --hc-cyan:    #06b6d4;
    --hc-teal:    #0e7490;
    --hc-muted:   #6aabab;
    --hc-subtle:  #94c9c9;
    --hc-text:    #1a3a3a;
}

/* Font */
body, .fi-body { font-family: "Plus Jakarta Sans", ui-sans-serif, system-ui, sans-serif !important; }

/* Background */
.fi-main, .fi-main-ctn, .fi-body { background-color: var(--hc-bg) !important; }

/* ── Sidebar ── */
.fi-sidebar { background: #fff !important; border-right: 1px solid var(--hc-border) !important; }
.fi-sidebar-header { background: #fff !important; border-bottom: 1px solid var(--hc-100) !important; }
.fi-logo img { border-radius: 50% !important; width: 42px !important; height: 42px !important; object-fit: cover !important; }
.fi-sidebar-nav-group-label { color: var(--hc-subtle) !important; font-size: 0.65rem !important; font-weight: 600 !important; text-transform: uppercase !important; letter-spacing: .08em !important; }
.fi-sidebar-item-button { border-radius: 10px !important; color: var(--hc-muted) !important; font-size: .875rem !important; margin: 1px 6px !important; transition: background .15s, color .15s !important; }
.fi-sidebar-item-button:hover { background: var(--hc-100) !important; color: var(--hc-teal) !important; }
.fi-sidebar-item-button.fi-active,
.fi-sidebar-item-button[aria-current="page"] { background: var(--hc-200) !important; color: var(--hc-teal) !important; font-weight: 500 !important; }
.fi-sidebar-item-button.fi-active svg,
.fi-sidebar-item-button[aria-current="page"] svg { color: var(--hc-cyan) !important; }
.fi-sidebar-footer { border-top: 1px solid var(--hc-100) !important; background: #fff !important; }

/* ── Topbar ── */
.fi-topbar, .fi-topbar nav { background: #fff !important; border-bottom: 1px solid var(--hc-border) !important; box-shadow: none !important; }

/* ── Stat Widget ── */
.fi-wi-stats-overview-stat { background: #fff !important; border: 1px solid var(--hc-border) !important; border-radius: 14px !important; box-shadow: none !important; position: relative; overflow: hidden; }
.fi-wi-stats-overview-stat::before { content: ""; position: absolute; inset: 0 0 auto 0; height: 3px; background: linear-gradient(90deg, var(--hc-aqua), var(--hc-cyan-lt)); border-radius: 14px 14px 0 0; }
.fi-wi-stats-overview-stat-label { color: var(--hc-muted) !important; font-size: .75rem !important; }
.fi-wi-stats-overview-stat-value { color: var(--hc-teal) !important; font-weight: 700 !important; font-size: 1.6rem !important; }
.fi-wi-stats-overview-stat-description { color: var(--hc-subtle) !important; font-size: .75rem !important; }
.fi-wi-stats-overview-stat-icon { background: #e0fdf8 !important; border-radius: 10px !important; color: var(--hc-cyan) !important; }

/* ── Section / Card ── */
.fi-section, .fi-card, .fi-wi-account, .fi-wi-filament-info { background: #fff !important; border: 1px solid var(--hc-border) !important; border-radius: 14px !important; box-shadow: none !important; }
.fi-section-header { border-bottom: 1px solid var(--hc-100) !important; }
.fi-section-header-heading { color: var(--hc-teal) !important; font-weight: 600 !important; }

/* ── Table ── */
.fi-ta-table-wrapper, .fi-ta-content { border-radius: 14px !important; border: 1px solid var(--hc-border) !important; background: #fff !important; }
.fi-ta-header-cell { background: var(--hc-bg) !important; color: var(--hc-subtle) !important; font-size: .7rem !important; font-weight: 600 !important; text-transform: uppercase !important; letter-spacing: .06em !important; border-bottom: 1px solid var(--hc-border) !important; }
.fi-ta-row { border-bottom: 1px solid var(--hc-bg) !important; transition: background .1s !important; }
.fi-ta-row:hover td, .fi-ta-row:hover { background: #f5fdfc !important; }
.fi-ta-cell { color: var(--hc-text) !important; font-size: .875rem !important; }
.fi-ta-search-field input { background: var(--hc-bg) !important; border: 1px solid var(--hc-border) !important; border-radius: 10px !important; }
.fi-ta-search-field input:focus { border-color: var(--hc-cyan) !important; box-shadow: 0 0 0 3px rgba(6,182,212,.12) !important; outline: none !important; }
.fi-pagination-item-btn[aria-current="page"], .fi-pagination-item-btn.fi-active { background: var(--hc-cyan) !important; color: #fff !important; border-radius: 8px !important; border-color: var(--hc-cyan) !important; }

/* ── Form Input ── */
.fi-input, .fi-select-input, .fi-textarea { background: var(--hc-bg) !important; border: 1px solid var(--hc-border) !important; border-radius: 10px !important; color: var(--hc-text) !important; font-size: .875rem !important; transition: border-color .15s, box-shadow .15s !important; }
.fi-input:focus, .fi-select-input:focus, .fi-textarea:focus { border-color: var(--hc-cyan) !important; box-shadow: 0 0 0 3px rgba(6,182,212,.12) !important; background: #fff !important; }
.fi-fo-field-wrp-label label, label.fi-label { color: var(--hc-teal) !important; font-size: .8rem !important; font-weight: 500 !important; }
.fi-fo-helper-text { color: var(--hc-subtle) !important; font-size: .75rem !important; }

/* ── Button ── */
.fi-btn.fi-color-primary { background: linear-gradient(135deg, var(--hc-cyan-lt), var(--hc-cyan)) !important; border: none !important; border-radius: 10px !important; color: #fff !important; font-weight: 500 !important; box-shadow: 0 2px 8px rgba(6,182,212,.25) !important; transition: opacity .15s, transform .1s !important; }
.fi-btn.fi-color-primary:hover { opacity: .9 !important; transform: translateY(-1px) !important; }
.fi-btn.fi-color-gray { background: #fff !important; border: 1px solid var(--hc-border) !important; border-radius: 10px !important; color: var(--hc-teal) !important; }
.fi-btn.fi-color-gray:hover { background: var(--hc-bg) !important; border-color: var(--hc-cyan) !important; }
.fi-btn.fi-color-danger { background: linear-gradient(135deg, #fb7185, #f43f5e) !important; border: none !important; border-radius: 10px !important; color: #fff !important; }

/* ── Badge ── */
.fi-badge { border-radius: 20px !important; font-size: .7rem !important; font-weight: 500 !important; padding: 2px 10px !important; }
.fi-color-success.fi-badge { background: #d5faf5 !important; color: #0d9488 !important; }
.fi-color-warning.fi-badge { background: #fef3c7 !important; color: #d97706 !important; }
.fi-color-danger.fi-badge  { background: #ffe4e6 !important; color: #e11d48 !important; }
.fi-color-info.fi-badge    { background: #cffafe !important; color: var(--hc-teal) !important; }
.fi-color-primary.fi-badge { background: var(--hc-200) !important; color: var(--hc-teal) !important; }

/* ── Modal ── */
.fi-modal-window { background: #fff !important; border: 1px solid var(--hc-border) !important; border-radius: 16px !important; box-shadow: 0 20px 60px rgba(14,116,144,.12) !important; }
.fi-modal-header { border-bottom: 1px solid var(--hc-100) !important; }
.fi-modal-heading { color: var(--hc-teal) !important; font-weight: 600 !important; }
.fi-modal-footer { border-top: 1px solid var(--hc-100) !important; background: var(--hc-bg) !important; border-radius: 0 0 16px 16px !important; }

/* ── Dropdown ── */
.fi-dropdown-panel { background: #fff !important; border: 1px solid var(--hc-border) !important; border-radius: 12px !important; box-shadow: 0 8px 24px rgba(14,116,144,.1) !important; }
.fi-dropdown-list-item-button { color: var(--hc-text) !important; font-size: .875rem !important; border-radius: 8px !important; }
.fi-dropdown-list-item-button:hover { background: var(--hc-bg) !important; color: var(--hc-teal) !important; }

/* ── Tabs ── */
.fi-tabs-tab { color: var(--hc-muted) !important; font-size: .875rem !important; }
.fi-tabs-tab:hover { color: var(--hc-teal) !important; }
.fi-tabs-tab[aria-selected="true"] { color: var(--hc-cyan) !important; border-bottom-color: var(--hc-cyan) !important; font-weight: 500 !important; }

/* ── Notification ── */
.fi-no-notification { border-radius: 12px !important; border: 1px solid var(--hc-border) !important; box-shadow: 0 8px 24px rgba(6,182,212,.12) !important; }

/* ── Login Page ── */
.fi-simple-layout { background: linear-gradient(135deg, #f0fafa 0%, #e0f5f2 50%, #cffafe 100%) !important; min-height: 100vh !important; }
.fi-simple-main { background: #fff !important; border: 1px solid var(--hc-border) !important; border-radius: 20px !important; box-shadow: 0 20px 60px rgba(14,116,144,.1) !important; }
.fi-simple-header img { border-radius: 50% !important; width: 80px !important; height: 80px !important; object-fit: cover !important; border: 3px solid var(--hc-border) !important; }
.fi-simple-header-heading { color: var(--hc-teal) !important; font-weight: 700 !important; }
.fi-simple-header-subheading { color: var(--hc-muted) !important; }

/* ── Icon Action Button ── */
.fi-icon-btn { color: var(--hc-muted) !important; border-radius: 8px !important; transition: background .15s, color .15s !important; }
.fi-icon-btn:hover { background: var(--hc-bg) !important; color: var(--hc-teal) !important; }

/* ── Scrollbar ── */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: var(--hc-bg); }
::-webkit-scrollbar-thumb { background: var(--hc-border); border-radius: 99px; }
::-webkit-scrollbar-thumb:hover { background: var(--hc-aqua); }
</style>
'))

            // ── Resources & Pages ─────────────────────────────────
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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
        Livewire::component('app.filament.auth.custom-login', CustomLogin::class);
    }
}