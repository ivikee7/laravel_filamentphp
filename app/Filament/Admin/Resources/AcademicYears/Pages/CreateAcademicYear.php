<?php

namespace App\Filament\Admin\Resources\AcademicYears\Pages;

use App\Filament\Admin\Resources\AcademicYears\AcademicYearResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAcademicYear extends CreateRecord
{
    protected static string $resource = AcademicYearResource::class;
}
