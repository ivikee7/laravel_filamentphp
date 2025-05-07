<?php

namespace App\Filament\Admin\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login as AuthLogin;
use Illuminate\Validation\ValidationException;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Route;

class Login extends AuthLogin
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
