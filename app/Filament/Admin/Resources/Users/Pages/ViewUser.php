<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use Filament\Actions\EditAction;
use Filament\Actions\Action;
use App\Filament\Admin\Resources\Users\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('ID-Card')
                ->url(fn(): string => UserResource::getUrl('id-card', [$this->record->id])),
            Action::make('Transport')
                ->url(fn(): string => UserResource::getUrl('transport', [$this->record->id])),
            Action::make('MonthlyAttendance')
                ->url(fn(): string => UserResource::getUrl('monthly-attendance', [$this->record->id])),
        ];
    }
}
