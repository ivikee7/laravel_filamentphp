<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use App\Filament\Admin\Resources\Stores\Widgets\CollectionStatusChart;
use App\Filament\Admin\Resources\Stores\Widgets\CollectionStatusTableWidget;
use App\Filament\Admin\Resources\Stores\Widgets\DailyCollectionTableWidget;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStore extends ViewRecord
{
    protected static string $resource = StoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('list-students')->url(StoreResource::getUrl('list-students', ['record' => $this->record])),
//            Action::make('invoices')->url(StoreResource::getUrl('invoices', ['record' => $this->record])),
//            Action::make('transactions')->url(StoreResource::getUrl('transactions', ['record' => $this->record])),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            DailyCollectionTableWidget::class,
            CollectionStatusChart::class,
        ];
    }
}
