<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class IDCard extends Page
{
    use InteractsWithRecord;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.i-d-card';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('View')
                ->url(fn(): string => UserResource::getUrl('view', [$this->record->id])),
            Actions\Action::make('Transport')
                ->url(fn(): string => UserResource::getUrl('transport', [$this->record->id])),
        ];
    }

    public function getModel(): string
    {
        return User::class;
    }
}
