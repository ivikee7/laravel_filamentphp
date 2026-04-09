<?php

namespace App\Filament\Admin\Resources\Enquiries\Pages;

use App\Filament\Admin\Resources\Enquiries\EnquiryResource;
use App\Filament\Admin\Resources\Enquiries\Widgets\EnquiryWidget;
use App\Filament\Admin\Resources\Transport\Routes\RouteResource;
use App\Models\Enquiry;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListEnquiries extends ListRecords
{
    protected static string $resource = EnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            EnquiryWidget::class,
        ];
    }

    public function getTabs(): array
    {
        $statuses = Enquiry::query()->distinct()->pluck('source');

        $tabs = [
            'all' => Tab::make('All')
                // Using a closure (fn) makes the badge dynamic/real-time
                ->badge(fn() => $this->makeBadgeQuery()->count()),
            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('registration'))
                ->badge(fn() => $this->makeBadgeQuery()->whereHas('registration')->count())
                ->badgeColor('success'),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereDoesntHave('registration'))
                ->badge(fn() => $this->makeBadgeQuery()->whereDoesntHave('registration')->count())
                ->badgeColor('warning'),
        ];

//        foreach ($statuses as $status) {
//            $tabs[$status] = Tab::make(ucfirst($status))
//                ->modifyQueryUsing(fn(Builder $query) => $query->where('source', $status))
//                ->badge(fn() => $this->makeBadgeQuery()->where('source', $status)->count())
//                ->badgeColor('danger');
//        }

        return $tabs;
    }

    /**
     * Helper to apply active table filters to the badge query
     */
    protected function makeBadgeQuery(): Builder
    {
        return $this->applyFiltersToTableQuery(Enquiry::query());
    }
}
