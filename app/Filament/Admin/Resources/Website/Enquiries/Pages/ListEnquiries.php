<?php

namespace App\Filament\Admin\Resources\Website\Enquiries\Pages;

use App\Filament\Admin\Resources\Website\Enquiries\Widgets\WebsiteEnquiryWidget;
use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Website\Enquiries\EnquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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
          WebsiteEnquiryWidget::class,
        ];
    }
}
