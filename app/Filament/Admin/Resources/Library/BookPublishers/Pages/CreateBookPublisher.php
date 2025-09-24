<?php

namespace App\Filament\Admin\Resources\Library\BookPublishers\Pages;

use App\Filament\Admin\Resources\Library\BookPublishers\BookPublisherResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookPublisher extends CreateRecord
{
    protected static string $resource = BookPublisherResource::class;
}
