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

    #[Url(keep: true)]
    public ?string $filter_date = null;

    // 1. Listen for Livewire updates to the URL query strings
    protected $listeners = [
        'urlQueryStringUpdated' => '$refresh',
    ];

    public function mount(): void
    {
        if (! $this->filter_date) {
            $this->filter_date = Carbon::today()->toDateString();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('filter')
                ->label(fn () => 'Date: ' . Carbon::parse($this->filter_date)->format('M d, Y'))
                ->icon('heroicon-m-funnel')
                ->color('gray')
                ->mountUsing(fn ($form) => $form->fill(['date' => $this->filter_date]))
                ->form([
                    DatePicker::make('date')
                        ->label('Select Attendance Date')
                        ->native(false)
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->filter_date = Carbon::parse($data['date'])->toDateString();

                    // 2. Broadcast the fresh target date directly to any listening widgets
                    $this->dispatch('refreshAttendanceWidget', selectedDate: $this->filter_date);
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RoleAttendanceOverview::class,
        ];
    }

    protected function getHeaderWidgetsProperties(): array
    {
        return [
            RoleAttendanceOverview::class => [
                'selectedDate' => $this->filter_date ?? Carbon::today()->toDateString(),
            ],
        ];
    }

}
