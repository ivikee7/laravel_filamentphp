<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('ID-Card')
                ->url(fn(): string => UserResource::getUrl('id-card', [$this->record->id])),
            Actions\Action::make('Transport')
                ->url(fn(): string => UserResource::getUrl('transport', [$this->record->id])),
            Actions\Action::make('MonthlyAttendance')
                ->url(fn(): string => UserResource::getUrl('monthly-attendance', [$this->record->id])),
        ];
    }
}
