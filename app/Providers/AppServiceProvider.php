<?php

namespace App\Providers;

use Filament\Forms\Components\Field;
use Illuminate\Support\ServiceProvider;

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
        Field::configureUsing(function (Field $field): void {
            $field->extraInputAttributes([
                'autocomplete' => 'off',
            ]);
        });
    }
}
