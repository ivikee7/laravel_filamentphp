<?php

namespace App\Filament\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class StudentExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),
            ExportColumn::make('name')->label('Name'),
            ExportColumn::make('email')->label('Email'),
            ExportColumn::make('gSuiteUser.email')->label('Official Email'),
            ExportColumn::make('gSuiteUser.password')->label('Official Email Password'),
            ExportColumn::make('father_name')->label('Father Name'),
            ExportColumn::make('mother_name')->label('Mother Name'),
            ExportColumn::make('primary_contact_number')->label('Primary Contact Number'),
            ExportColumn::make('secondary_contact_number')->label('Secondary Contact Number'),
            ExportColumn::make('address')->label('Address'),
            ExportColumn::make('city')->label('City'),
            ExportColumn::make('state')->label('State'),
            ExportColumn::make('pin_code')->label('Pin Code'),
            ExportColumn::make('avatar')->label('Avatar'),
            ExportColumn::make('is_active')->label('Is Active'),
            ExportColumn::make('bloodGroup.name')->label('Blood Group'),
            ExportColumn::make('gender.name')->label('Gender'),
            ExportColumn::make('aadhaar_number')->label('Aadhaar Number'),
            ExportColumn::make('mother_tongue')->label('Mother Tongue'),
            ExportColumn::make('date_of_birth')->label('Date Of Birth'),
            ExportColumn::make('place_of_birth')->label('Place Of Birth'),
            ExportColumn::make('notes')->label('Notes'),
            ExportColumn::make('termination_date')->label('Termination Date'),
            // Registration
            ExportColumn::make('currentStudent.registration_id')->label('Registration ID'),
            ExportColumn::make('currentStudent.quota.name')->label('Quota'),
            ExportColumn::make('currentStudent.admission_number')->label('Admission Number'),
            ExportColumn::make('currentStudent.current_status')->label('Current Status'),
            ExportColumn::make('currentStudent.tc_status')->label('TC Status'),
            ExportColumn::make('currentStudent.leaving_date')->label('Leaving Date'),
            ExportColumn::make('currentStudent.exit_reason')->label('Exit Reason'),
            ExportColumn::make('currentStudent.local_guardian_user_id.name')->label('Local Guardian User'),
            ExportColumn::make('currentStudent.local_guardian_relationship')->label('Local Guardian Relationship'),
            // Auth
            ExportColumn::make('createdBy.name')->label('Created By'),
            ExportColumn::make('updatedBy.name')->label('Updated By'),
            ExportColumn::make('deletedBy.name')->label('Deleted By'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your student export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
