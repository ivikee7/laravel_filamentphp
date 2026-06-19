<?php

namespace App\Filament\Admin\Resources\Attendances\Pages;

use App\Filament\Admin\Resources\Attendances\AttendanceResource;
use App\Filament\Admin\Resources\Attendances\Widgets\RoleAttendanceOverview;
use Carbon\Carbon;
use Filament\Actions\Action;
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
    protected static string $resource = AttendanceResource::class;

    protected string $view = 'filament.admin.resources.attendances.pages.attendance-dashbaord';

    // This automatically captures the selection from the page's query string
    #[Url(keep: true)]
    public ?string $filter_date = null;

    public function mount(): void
    {
        if (!$this->filter_date) {
            $this->filter_date = Carbon::today()->toDateString();
        }
    }

    /**
     * Display a beautiful action drop-button in your page header container
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('filter')
                ->label(fn() => 'Date: ' . Carbon::parse($this->filter_date)->format('M d, Y'))
                ->icon('heroicon-m-funnel')
                ->color('gray')
                ->mountUsing(fn($form) => $form->fill(['date' => $this->filter_date]))
                ->form([
                    DatePicker::make('date')
                        ->label('Select Attendance Date')
                        ->native(false)
                        ->required(),
                ])
                ->action(function (array $data): void {
                    // Update state, mutating the URL parameter and refreshing the entire page loop
                    $this->filter_date = Carbon::parse($data['date'])->toDateString();
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RoleAttendanceOverview::class,
        ];
    }

    /**
     * MANDATORY: Forwards the active page URL filter straight into the widget properties
     */
    protected function getHeaderWidgetsProperties(): array
    {
        return [
            RoleAttendanceOverview::class => [
                'selectedDate' => $this->filter_date ?? Carbon::today()->toDateString(),
            ],
        ];
    }

}
