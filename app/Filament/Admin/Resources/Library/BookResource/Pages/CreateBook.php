<?php

namespace App\Filament\Admin\Resources\Library\BookResource\Pages;

use App\Filament\Admin\Resources\Library\BookResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBook extends CreateRecord
{
    protected static string $resource = BookResource::class;
}
