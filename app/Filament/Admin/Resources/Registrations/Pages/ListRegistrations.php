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
        return [
            'all' => Tab::make('All'),

            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('student'))
                ->badge(static::getResource()::getModel()::whereHas('student')->count())
                ->badgeColor('success'),

            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDoesntHave('student'))
                ->badge(static::getResource()::getModel()::whereDoesntHave('student')->count())
                ->badgeColor('warning'),
        ];
    }
}
