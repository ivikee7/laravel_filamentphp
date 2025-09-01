<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\Stores\Resources\Students\Pages;

use App\Filament\Admin\Resources\StoreManagementSystem\Stores\Resources\Students\StudentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;
}
