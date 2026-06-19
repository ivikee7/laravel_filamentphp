<?php

namespace App\Filament\Admin\Resources\Attendances\Pages;

use App\Filament\Admin\Resources\Attendances\AttendanceResource;
use App\Filament\Admin\Resources\Attendances\Widgets\RoleAttendanceOverview;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AttendanceDashbaord extends Page
{
    use HasFiltersAction; // <-- Activates header layout injection

    protected static string $resource = AttendanceResource::class;

    protected string $view = 'filament.admin.resources.attendances.pages.attendance-dashbaord';


    /**
     * Move the filter logic strictly into the Page Header actions zone
     */
    protected function getHeaderActions(): array
    {
        return [
            FilterAction::make()
                ->schema([
                    DatePicker::make('filter_date')
                        ->label('Attendance Date')
                        ->default(Carbon::today())
                        ->required(),
                ]),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RoleAttendanceOverview::class,
        ];
    }

}
