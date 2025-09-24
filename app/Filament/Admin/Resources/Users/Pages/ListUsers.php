<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use App\Filament\Admin\Resources\Attendances\AttendanceResource;
use App\Filament\Admin\Resources\Users\UserResource;
use Filament\Actions;
use Filament\Tables;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('MonthlyAttendance')
                ->url(fn(): string => UserResource::getUrl('monthly-attendance')),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            // ... other actions

        ];
    }
}
