<?php

namespace App\Providers\Filament;

use App\Filament\Student\Pages\Dashboard;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class StudentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('student')
            ->path('student')
            ->viteTheme('resources/css/filament/student/theme.css')
            ->login()
            ->colors([
                'primary' => Color::Teal,
            ])
            ->brandName(env('APP_NAME') . ' Student')
            ->discoverResources(in: app_path('Filament/Student/Resources'), for: 'App\Filament\Student\Resources')
            ->discoverPages(in: app_path('Filament/Student/Pages'), for: 'App\Filament\Student\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Student/Widgets'), for: 'App\Filament\Student\Widgets')
            ->widgets([
                AccountWidget::class,
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
            ->favicon(asset('storage/media/logo_favicon.png'))
            ->maxContentWidth(Width::Full)
            ->userMenuItems([
                Action::make('switch_to_admin_panel')
                    ->label('Switch to Admin Panel')
                    ->icon(Heroicon::Squares2x2)
                    ->url(fn (): string => route('filament.admin.pages.dashboard'))
                    ->visible(function (): bool {
                        $user = auth()->user();
                        $currentPanelId = Filament::getCurrentPanel()?->getId();
                        $adminPanel = Filament::getPanel('admin', false);

                        return filled($user)
                            && method_exists($user, 'isSuperAdmin')
                            && $user->isSuperAdmin()
                            && $currentPanelId !== 'admin'
                            && filled($adminPanel)
                            && $user->canAccessPanel($adminPanel);
                    }),
            ]);
    }
}
