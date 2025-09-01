<?php

namespace App\Filament\Admin\Resources\StudentClasses\Pages;

use App\Filament\Admin\Resources\StudentClasses\StudentClassResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClasses extends CreateRecord
{
    protected static string $resource = StudentClassResource::class;
}
