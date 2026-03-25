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

            // Use safe dot notation or closures for relationships
            ExportColumn::make('gSuiteUser.email')->label('Official Email'),
            ExportColumn::make('gSuiteUser.password')->label('Official Email Password'),

            ExportColumn::make('father_name')->label('Father Name'),
            ExportColumn::make('mother_name')->label('Mother Name'),
            ExportColumn::make('primary_contact_number')->label('Primary Contact Number'),
            ExportColumn::make('secondary_contact_number')->label('Secondary Contact Number'),

            ExportColumn::make('full_address')
                ->label('Full Address')
                ->state(fn (User $record): string =>
                collect([$record->address, $record->city, $record->state, $record->pin_code])
                    ->filter()
                    ->implode(', ')
                ),

            ExportColumn::make('is_active')
                ->label('Status')
                // Remove bool typehint to prevent crashes on null values
                ->formatStateUsing(fn($state): string => $state ? 'Active' : 'Suspended'),

            ExportColumn::make('bloodGroup.name')->label('Blood Group'),
            ExportColumn::make('gender.name')->label('Gender'),

            // Student Related - Using null-safe access
            ExportColumn::make('student.registration_id')->label('Registration ID'),
            ExportColumn::make('student.quota.name')->label('Quota'),
            ExportColumn::make('student.admission_number')->label('Admission Number'),
            ExportColumn::make('student.current_status')->label('Current Status'),

            // FIX: If local_guardian_user_id is a column name,
            // you must use the RELATIONSHIP name instead (e.g., 'guardian')
            ExportColumn::make('student.guardian.name')->label('Local Guardian User'),

            // Deep Class Assignments - Use state() to prevent "property of non-object" crashes
            ExportColumn::make('academic_year')
                ->label('Academic Year')
                ->state(fn (User $record) => $record->student?->classAssignment?->academicYear?->name),

            ExportColumn::make('class')
                ->label('Class')
                ->state(fn (User $record) => $record->student?->classAssignment?->class?->className?->name),

            ExportColumn::make('section')
                ->label('Section')
                ->state(fn (User $record) => $record->student?->classAssignment?->section?->name),

            // Auth
            ExportColumn::make('createdBy.name')->label('Created By'),
            ExportColumn::make('created_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your student export has completed and ' . number_format($export->successful_rows) . ' ' . Str::plural('row', $export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . Str::plural('row', $failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
