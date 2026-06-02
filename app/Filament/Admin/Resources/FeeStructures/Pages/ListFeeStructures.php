<?php

namespace App\Filament\Admin\Resources\FeeStructures\Pages;

use App\Filament\Admin\Resources\FeeStructures\FeeStructureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeeStructures extends ListRecords
{
    protected static string $resource = FeeStructureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

