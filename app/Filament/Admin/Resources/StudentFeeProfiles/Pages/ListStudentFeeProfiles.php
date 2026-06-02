<?php

namespace App\Filament\Admin\Resources\StudentFeeProfiles\Pages;

use App\Filament\Admin\Resources\StudentFeeProfiles\StudentFeeProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudentFeeProfiles extends ListRecords
{
    protected static string $resource = StudentFeeProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

