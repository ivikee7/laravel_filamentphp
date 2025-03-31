<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\AttendanceResource;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Tables;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('MonthlyAttendance')
                ->url(fn(): string => UserResource::getUrl('monthly-attendance')),
            Actions\Action::make('MinimalTest ')
                ->url(fn(): string => UserResource::getUrl('minimal-test')),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            // ... other actions

        ];
    }
}
