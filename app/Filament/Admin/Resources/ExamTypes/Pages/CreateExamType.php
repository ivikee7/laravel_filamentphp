<?php

namespace App\Filament\Admin\Resources\ExamTypes\Pages;

use App\Filament\Admin\Resources\ExamTypes\ExamTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExamType extends CreateRecord
{
    protected static string $resource = ExamTypeResource::class;
}
