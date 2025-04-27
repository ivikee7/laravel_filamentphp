<?php

namespace App\Filament\Admin\Resources\StudentClassResource\Pages;

use App\Filament\Admin\Resources\StudentClassResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClasses extends CreateRecord
{
    protected static string $resource = StudentClassResource::class;
}
