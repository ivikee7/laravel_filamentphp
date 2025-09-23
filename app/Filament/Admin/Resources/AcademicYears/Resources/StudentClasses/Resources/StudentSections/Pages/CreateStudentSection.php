<?php

namespace App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\Pages;

use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\StudentSectionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentSection extends CreateRecord
{
    protected static string $resource = StudentSectionResource::class;
}
