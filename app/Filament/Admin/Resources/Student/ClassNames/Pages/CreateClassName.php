<?php

namespace App\Filament\Admin\Resources\Student\ClassNames\Pages;

use App\Filament\Admin\Resources\Student\ClassNames\ClassNameResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClassName extends CreateRecord
{
    protected static string $resource = ClassNameResource::class;
}
