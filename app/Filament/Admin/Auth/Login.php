<?php

namespace App\Filament\Admin\Auth;

use Filament\Schemas\Components\View;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\ValidationException;

class Login extends \Filament\Auth\Pages\Login
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->components([
                        $this->getIdFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                        View::make('components.login.google-button')  // Add Google button here.
                            ->columnSpanFull(), // Make the button span the entire row
                    ])
                    ->statePath('data')
                    ->columns(1), // Ensure the form has only one column
            ),
        ];
    }

    protected function getIdFormComponent(): Component
    {
        return TextInput::make('id')
            ->label(__('User ID'))
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'id' => $data['id'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.id' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getIdFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
                View::make('components.login.google-button')  // Add Google button here.
                    ->columnSpanFull(),
            ])->columns(1);
    }

    public function mount(): void
    {
        parent::mount();

        $this->form->fill(); // Fill the form with any initial data
    }
}
