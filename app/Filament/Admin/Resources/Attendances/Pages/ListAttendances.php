<?php

namespace App\Filament\Admin\Resources\Attendances\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Attendances\AttendanceResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ListRecords;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ActionGroup::make([
                Action::make('monthlyReport')
                    ->label('Monthly Report')
                    ->icon('heroicon-o-chart-bar')
                    ->url(fn() => AttendanceResource::getUrl('monthly-report')),
            ])
                ->label('Attendance Reports') // ✅ This will show if dropdown is enabled
                ->icon('heroicon-o-chart-bar')
                ->dropdown(), // ✅ This turns it into a labeled dropdown

        ];
    }
}
