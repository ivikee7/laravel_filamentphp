<?php

namespace App\Providers\Filament;

use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Admin\Auth\Login;
use App\Filament\Admin\Pages\ViewLog;
use App\Filament\Pages\HealthCheckResults;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Resources\Pages\Page as PagesPage;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Table;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Saade\FilamentLaravelLog\FilamentLaravelLogPlugin;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('/admin')
            ->login(Login::class)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
            ->navigationGroups([
                'User',
                'School Management System',
                'Transport',
                'Library Management System',
                'Store Management System',
                'SMS Services',
                'G-Suite',
                'WhatsApp',
            ])
            ->plugin(FilamentSpatieRolesPermissionsPlugin::make())
            ->plugin(
                FilamentSpatieLaravelHealthPlugin::make()
                    ->authorize(function (): bool {
                        // Check if the current user has the 'Super Admin' role
                        return Auth::check() && Auth::user()->hasRole('Super Admin');
                    })
            )
            ->plugin(FilamentLaravelLogPlugin::make()
                ->authorize(function (): bool {
                    // Check if the current user has the 'Super Admin' role
                    return Auth::check() && Auth::user()->hasRole('Super Admin');
                })
                ->viewLog(ViewLog::class)
                ->navigationGroup('Settings')
                ->navigationLabel('Logs')
                ->navigationIcon('heroicon-o-bug-ant')
                ->navigationSort(1)
                ->slug('logs'))
            ->spa()
            ->maxContentWidth(MaxWidth::Full)
            ->sidebarFullyCollapsibleOnDesktop()
            ->bootUsing(function () {
                Table::configureUsing(function (Table $table): void {
                    $table->paginated([5, 10, 25, 50])
                        ->defaultPaginationPageOption(5);
                });
            })
            ->favicon(asset('logo_favicon.png'))
            // ->passwordReset() // Password Reset
            ->profile() // Profile
        ;
    }
}
