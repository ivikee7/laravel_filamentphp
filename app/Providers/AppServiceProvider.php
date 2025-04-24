<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
//
use Spatie\Health\Facades\Health;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DatabaseConnectionCountCheck;
use Spatie\Health\Checks\Checks\CpuLoadCheck;
use Spatie\Health\Checks\Checks\DatabaseSizeCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
// use Spatie\Health\Checks\Checks\DatabaseConnectionCountCheck;
use Spatie\Health\Checks\Checks\QueueCheck;
use Spatie\Health\Checks\Checks\ScheduleCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Spatie\Health\Checks\Checks\HttpCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;

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
        Gate::policy(
            \App\Filament\Admin\Pages\IDCards\ListIDCards::class,
            \App\Policies\IDCards\ListIDCardsPolicy::class
        );
        Gate::policy(
            \App\Filament\Admin\Pages\IDCards\ViewIDCard::class,
            \App\Policies\IDCards\ViewIDCardPolicy::class
        );

        Health::checks([
            OptimizedAppCheck::new(),
            DebugModeCheck::new(),
            EnvironmentCheck::new(),

            // database
            DatabaseCheck::new(),
            DatabaseConnectionCountCheck::new('mysql'),
            DatabaseSizeCheck::new(),

            // Queue Cache Schedule
            QueueCheck::new(),
            ScheduleCheck::new(),
            CacheCheck::new(),
            // ping
            PingCheck::new()->url('erp.srcspatna.com'),
            // HttpCheck::new()->url('https://your-api.com/health')->name('API Health'),

            // Disk
            UsedDiskSpaceCheck::new()->warnWhenUsedSpaceIsAbovePercentage(70),
        ]);
    }
}
