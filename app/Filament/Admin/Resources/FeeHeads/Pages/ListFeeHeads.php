<?php

namespace App\Filament\Admin\Resources\FeeHeads\Pages;

use App\Filament\Admin\Resources\FeeHeads\FeeHeadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeeHeads extends ListRecords
{
    protected static string $resource = FeeHeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

