<?php

namespace App\Providers;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        /**
         * Global FilamentPHP Autofill Killer
         *
         * This hook targets all form fields globally to prevent modern browsers (Chrome, Safari, Edge)
         * and password managers (1Password, LastPass) from forcing autofill/suggestions.
         *
         * It uses 'new-password' for sensitive fields to bypass aggressive browser heuristics
         * and applies unique/random attributes to other fields to prevent data matching.
         */
        Field::configureUsing(function (Field $field): void {
            // Generate a random string to "trick" the browser's matching logic
            $garbageValue = 'no-fill-' . Str::random(8);

            // 1. Apply to standard inputs (Select, DateTime, etc.)
            if (method_exists($field, 'extraInputAttributes')) {
                $field->extraInputAttributes([
                    'autocomplete' => $garbageValue,
                    'data-lpignore' => 'true', // Disable LastPass
                    'data-1p-ignore' => 'true', // Disable 1Password
                ]);
            }

            // 2. Specialized handling for TextInputs (Email/Passwords)
            if ($field instanceof TextInput) {
                $field->extraInputAttributes([
                    'autocomplete' => 'new-password', // Force browser to treat as a fresh field
                ]);

                // Also hide password manager icons if it's a password field
                if (method_exists($field, 'hidePasswordManagerIcons')) {
                    $field->hidePasswordManagerIcons();
                }
            }
        });
    }
}
