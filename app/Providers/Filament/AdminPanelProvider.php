<?php

namespace App\Providers\Filament;

use Awcodes\LightSwitch\Enums\Alignment;
use Awcodes\LightSwitch\LightSwitchPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
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
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use TomatoPHP\FilamentUsers\FilamentUsersPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('')
            ->login()
            ->passwordReset()
            ->databaseNotifications()
            ->databaseTransactions()
            ->unsavedChangesAlerts()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->brandLogo(asset('/images/logo-lg.webp'))
            ->brandLogoHeight('40px')
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Supplier')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Customer')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Master Buku')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Produk')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Stok')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Estimasi')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Pengiriman')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(__('filament-shield::filament-shield.nav.group'))
                    ->collapsed(),
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
            ])
            ->plugins([
                BreezyCore::make(),
                LightSwitchPlugin::make()
                    ->position(Alignment::TopCenter),
                FilamentBackgroundsPlugin::make(),
                FilamentShieldPlugin::make(),
                FilamentUsersPlugin::make()
            ]);
    }
}
