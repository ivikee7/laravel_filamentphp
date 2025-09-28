<?php

namespace App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Pages;

use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\StudentClassResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentClass extends CreateRecord
{
    protected static string $resource = StudentClassResource::class;
}
