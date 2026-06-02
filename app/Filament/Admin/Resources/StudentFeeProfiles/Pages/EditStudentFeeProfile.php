<?php

namespace App\Filament\Admin\Resources\StudentFeeProfiles\Pages;

use App\Filament\Admin\Resources\StudentFeeProfiles\StudentFeeProfileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentFeeProfile extends EditRecord
{
    protected static string $resource = StudentFeeProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

