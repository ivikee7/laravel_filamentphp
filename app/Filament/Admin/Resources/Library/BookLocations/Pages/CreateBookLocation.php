<?php

namespace App\Filament\Admin\Resources\Library\BookLocations\Pages;

use App\Filament\Admin\Resources\Library\BookLocations\BookLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookLocation extends CreateRecord
{
    protected static string $resource = BookLocationResource::class;
}
