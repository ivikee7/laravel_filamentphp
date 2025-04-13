<?php

namespace App\Filament\Admin\Resources\Library\BookPublisherResource\Pages;

use App\Filament\Admin\Resources\Library\BookPublisherResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookPublisher extends CreateRecord
{
    protected static string $resource = BookPublisherResource::class;
}
