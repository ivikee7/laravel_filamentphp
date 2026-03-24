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
        // Fetch unique statuses from the database dynamically
        $statuses = Enquiry::query()->distinct()->pluck('source');

        $tabs = [
            'all' => Tab::make('All Enquiries')
                ->badge(Enquiry::query()->count()),
        ];

        foreach ($statuses as $status) {
            $tabs[$status] = Tab::make(ucfirst($status))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('source', $status))
                ->badge(Enquiry::query()->where('source', $status)->count()); // Optional: Add a badge count
        }

        return $tabs;
    }
}
