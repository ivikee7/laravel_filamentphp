<?php

namespace App\Filament\Admin\Resources\StudentSectionResource\Pages;

use App\Filament\Admin\Resources\StudentSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSection extends CreateRecord
{
    protected static string $resource = StudentSectionResource::class;
}
