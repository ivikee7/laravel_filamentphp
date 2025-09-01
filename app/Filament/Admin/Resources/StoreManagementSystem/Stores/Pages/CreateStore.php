<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\Stores\Pages;

use App\Filament\Admin\Resources\StoreManagementSystem\Stores\StoreResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStore extends CreateRecord
{
    protected static string $resource = StoreResource::class;
}
