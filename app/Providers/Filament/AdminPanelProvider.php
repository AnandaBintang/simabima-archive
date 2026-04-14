<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Enums\ThemeMode;
use Filament\View\PanelsRenderHook;
use App\Filament\Pages\EditProfile;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->profile(EditProfile::class, isSimple: false)
            ->brandName('SIMABIMA')
            ->brandLogo(asset('img/logo.png'))
            ->brandLogoHeight('2rem')
            ->colors([
                'primary' => Color::hex('#CDAB2F'),
                'gray'    => Color::Slate,
            ])
            ->defaultThemeMode(ThemeMode::Light)
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn (): string => <<<'CSS'
                <style>
                    /* ── Sidebar background ── */
                    .fi-sidebar {
                        background-color: #2A3890 !important;
                    }
                    .fi-sidebar-header {
                        background-color: #1e2a6e !important;
                        border-bottom: 1px solid rgba(255,255,255,.1) !important;
                    }
                    /* ── Brand name ── */
                    .fi-logo, .fi-brand-name {
                        color: #000000 !important;
                    }
                    /* ── Default item: white text + icon ── */
                    .fi-sidebar-item-label,
                    .fi-sidebar-group-label {
                        color: rgba(255,255,255,.85) !important;
                    }
                    .fi-sidebar-item-btn svg {
                        color: rgba(255,255,255,.6) !important;
                    }
                    /* ── Hover ── */
                    .fi-sidebar-item-btn:hover {
                        background-color: rgba(255,255,255,.08) !important;
                    }
                    /* ── Group collapse chevron ── */
                    .fi-sidebar-group-collapse-btn svg {
                        color: rgba(255,255,255,.5) !important;
                    }
                    /* ── ACTIVE item: yellow bg, dark text ── */
                    .fi-sidebar-item.fi-active .fi-sidebar-item-btn {
                        background-color: #CDAB2F !important;
                        border-radius: .5rem !important;
                    }
                    .fi-sidebar-item.fi-active .fi-sidebar-item-label {
                        color: #1e2a6e !important;
                        font-weight: 700 !important;
                    }
                    .fi-sidebar-item.fi-active .fi-sidebar-item-btn svg {
                        color: #1e2a6e !important;
                    }
                    /* ── Topbar / Navbar ── */
                    .fi-topbar {
                        background-color: #CDAB2F !important;
                        border-bottom: 1px solid rgba(0,0,0,.08) !important;
                    }
                    .fi-topbar .fi-icon-btn svg,
                    .fi-topbar .fi-btn,
                    .fi-topbar .fi-user-menu-trigger {
                        color: rgba(0,0,0,.75) !important;
                    }
                    .fi-breadcrumbs a, .fi-breadcrumbs span {
                        color: rgba(0,0,0,.6) !important;
                    }
                </style>
                CSS
            )
            ->maxContentWidth('full')
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                'Eksplorasi Arsip',
                'Arsip',
                'Master Data',
                'Pengguna',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
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
}
