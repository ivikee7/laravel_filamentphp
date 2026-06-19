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
    use HasFiltersAction;

    protected static string $resource = AttendanceResource::class;

    protected string $view = 'filament.admin.resources.attendances.pages.attendance-dashbaord';


    // 1. MANDATORY: Add this public property to hold the active header filters state
    public array $status = [];

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
                        ->native(false)
                        ->live() // 2. MANDATORY: Forces an instantaneous live refresh on value modification
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
