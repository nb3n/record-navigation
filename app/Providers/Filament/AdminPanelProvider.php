<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Saade\FilamentFacehash\FacehashPlugin;
use Saade\FilamentFacehash\FacehashProvider;
use Filament\Enums\ThemeMode;
use Filament\Support\Icons\Heroicon;
use Filament\Enums\UserMenuPosition;
use Filament\Actions\Action;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->colors([
                'primary' => [
                    50 => 'oklch(0.97 0.02 120)',
                    100 => 'oklch(0.94 0.03 120)',
                    200 => 'oklch(0.88 0.05 120)',
                    300 => 'oklch(0.80 0.08 120)',
                    400 => 'oklch(0.72 0.11 120)',
                    500 => 'oklch(0.65 0.14 120)',
                    600 => 'oklch(0.58 0.13 120)',
                    700 => 'oklch(0.50 0.11 120)',
                    800 => 'oklch(0.44 0.09 120)',
                    900 => 'oklch(0.40 0.07 120)',
                    950 => 'oklch(0.27 0.05 120)',
                ],
            ])
            ->defaultThemeMode(ThemeMode::Light)
            ->brandLogoHeight('2rem')
            ->brandName(config('filament-portal.brand'))
            ->brandLogo(getCdnUrl('media/company/logo-light.svg'))
            ->darkModeBrandLogo(getCdnUrl('media/company/logo-dark.svg'))
            ->favicon(getCdnUrl('media/company/favicon.svg'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->defaultAvatarProvider(FacehashProvider::class)
            ->plugins([
                FacehashPlugin::make(),
            ])
            ->databaseNotifications()
            ->userMenuItems([
                Action::make('github')
                    ->url(config('filament-portal.github'))
                    ->icon(Heroicon::OutlinedCommandLine),

                Action::make('sponsor')
                    ->url(config('filament-portal.sponsor'))
                    ->icon(Heroicon::OutlinedArchiveBox),

                Action::make('plugin')
                    ->url(config('filament-portal.plugin'))
                    ->icon(Heroicon::OutlinedPuzzlePiece),
            ])
            ->userMenu(position: UserMenuPosition::Sidebar)
            ->plugins([ 
                GlobalSearchModalPlugin::make()
                    ->showGroupSearchCounts(),
            ])
            ->spa()
            ->topbar(false)
            ->sidebarCollapsibleOnDesktop()
            ->collapsedSidebarWidth('9rem')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchFieldKeyBindingSuffix()
            ->globalSearchDebounce('750ms');
    }
}
