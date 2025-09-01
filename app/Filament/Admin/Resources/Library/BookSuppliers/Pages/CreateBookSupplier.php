<?php

namespace App\Filament\Admin\Resources\Library\BookSuppliers\Pages;

use App\Filament\Admin\Resources\Library\BookSuppliers\BookSupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookSupplier extends CreateRecord
{
    protected static string $resource = BookSupplierResource::class;
}
