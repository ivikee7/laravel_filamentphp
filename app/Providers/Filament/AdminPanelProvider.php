<?php

namespace App\Providers\Filament;

<<<<<<< Updated upstream
use App\Filament\Admin\Pages\IDCard;
use App\Filament\Admin\Widgets\AdmissionWidget;
use App\Filament\Admin\Widgets\EnquiryWidget;
use App\Filament\Admin\Widgets\RegistrationWidget;
use App\Filament\Admin\Widgets\TodayAdmissionWidget;
use App\Filament\Admin\Widgets\TodayRegistrationWidget;
use App\Models\Enquiry;
=======
use Filament\Actions\Action;
use Filament\Facades\Filament;
>>>>>>> Stashed changes
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Widgets\Widget;
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
            ->path('/admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandName(env('APP_NAME') .' '. 'Admin')
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\Filament\Admin\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\Filament\Admin\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\Filament\Admin\Widgets')
            ->widgets([
//                AccountWidget::class,
//                FilamentInfoWidget::class,
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
            ->spa()
            ->navigationGroups([
                'User',
                'School Management System',
                'LMS',
                'Website',
                'Transport',
                'Library Management System',
                'Store Management System',
                'SMS Services',
                'G-Suite',
                'WhatsApp',
            ])
            ->maxContentWidth(Width::Full)
            ->sidebarFullyCollapsibleOnDesktop()
            ->bootUsing(function () {
                Table::configureUsing(function (Table $table): void {
                    $table->paginated([5, 10, 25, 50])
                        ->defaultPaginationPageOption(5);
                });
            })
            ->favicon(asset('logo_favicon.png'))
            ->passwordReset() // Password Reset
            ->profile() // Profile
<<<<<<< Updated upstream
            ->readOnlyRelationManagersOnResourceViewPagesByDefault(false);
=======
            ->readOnlyRelationManagersOnResourceViewPagesByDefault(false)
            ->userMenuItems([
                Action::make('switch_to_student_panel')
                    ->label('Switch to Student Panel')
                    ->icon(Heroicon::AcademicCap)
                    ->url(fn (): string => route('filament.student.pages.dashboard'))
                    ->visible(function (): bool {
                        $user = auth()->user();
                        $currentPanelId = Filament::getCurrentPanel()?->getId();
                        $studentPanel = Filament::getPanel('student', false);

                        return filled($user)
                            && method_exists($user, 'isSuperAdmin')
                            && $user->isSuperAdmin()
                            && $currentPanelId !== 'student'
                            && filled($studentPanel)
                            && $user->canAccessPanel($studentPanel);
                    }),
            ]);

        if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))) {
            $panel->viteTheme('resources/css/filament/admin/theme.css');
        }

        return $panel;
>>>>>>> Stashed changes
    }
}
