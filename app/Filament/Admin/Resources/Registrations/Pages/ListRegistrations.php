<?php

namespace App\Filament\Admin\Resources\Registrations\Pages;

use App\Filament\Admin\Resources\Registrations\RegistrationResource;
use App\Filament\Admin\Resources\Registrations\Widgets\RegistrationAdmissionComparisonWidget;
use App\Filament\Admin\Resources\Registrations\Widgets\RegistrationAdmissionWidget;
use App\Filament\Admin\Resources\Registrations\Widgets\RegistrationEnquiryComparisonWidget;
use App\Filament\Admin\Resources\Registrations\Widgets\RegistrationWidget;
use App\Filament\Admin\Resources\Registrations\Widgets\RegistrationWithoutAdmissionWidget;
use App\Filament\Admin\Resources\Website\Enquiries\Widgets\WebsiteEnquiryWidget;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListRegistrations extends ListRecords
{
    protected static string $resource = RegistrationResource::class;

    public ?string $activeTab = 'pending';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            RegistrationWidget::class,
            RegistrationAdmissionWidget::class,
            RegistrationWithoutAdmissionWidget::class,
            RegistrationAdmissionComparisonWidget::class,
            RegistrationEnquiryComparisonWidget::class,
        ];
    }

    public function getTabs(): array
    {
        // Capture the selected Academic Year ID from the table filters
        // 'academic_year_id' must match the name of your SelectFilter
        $selectedYearId = $this->tableFilters['academic_year_id']['value'] ?? null;

        return [
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->when($selectedYearId, fn ($q) => $q->where('academic_year_id', $selectedYearId))
                    ->whereDoesntHave('student')
                )
                ->badge(fn () => static::getResource()::getModel()::query()
                    ->when($selectedYearId, fn ($q) => $q->where('academic_year_id', $selectedYearId))
                    ->whereDoesntHave('student')
                    ->count()
                )
                ->badgeColor('warning'),

            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->when($selectedYearId, fn ($q) => $q->where('academic_year_id', $selectedYearId))
                    ->whereHas('student')
                )
                ->badge(fn () => static::getResource()::getModel()::query()
                    ->when($selectedYearId, fn ($q) => $q->where('academic_year_id', $selectedYearId))
                    ->whereHas('student')
                    ->count()
                )
                ->badgeColor('success'),

            'all' => Tab::make('All')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->when($selectedYearId, fn ($q) => $q->where('academic_year_id', $selectedYearId))
                )
                ->badge(fn () => static::getResource()::getModel()::query()
                    ->when($selectedYearId, fn ($q) => $q->where('academic_year_id', $selectedYearId))
                    ->count()
                ),
        ];
    }

}
