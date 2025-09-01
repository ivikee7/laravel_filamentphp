<?php

namespace App\Filament\Admin\Resources\Genders\Pages;

use App\Filament\Admin\Resources\Genders\GenderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGender extends CreateRecord
{
    protected static string $resource = GenderResource::class;
}
