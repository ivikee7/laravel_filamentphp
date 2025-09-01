<?php

namespace App\Filament\Admin\Resources\Subjects\Pages;

use App\Filament\Admin\Resources\Subjects\SubjectResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSubject extends CreateRecord
{
    protected static string $resource = SubjectResource::class;
}
