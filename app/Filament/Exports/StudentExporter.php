<?php

namespace App\Filament\Exports;

use App\Models\Student;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class StudentExporter extends Exporter
{
    protected static ?string $model = Student::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('user.id')
                ->label('ID'),
            ExportColumn::make('user.name'),
            ExportColumn::make('user.email'),
            ExportColumn::make('user.official_email'),
            ExportColumn::make('user.father_name'),
            ExportColumn::make('user.mother_name'),
            ExportColumn::make('user.primary_contact_number'),
            ExportColumn::make('user.secondary_contact_number'),
            ExportColumn::make('user.address'),
            ExportColumn::make('user.city'),
            ExportColumn::make('user.state'),
            ExportColumn::make('user.pin_code'),
            ExportColumn::make('user.avatar'),
            ExportColumn::make('user.is_active'),
            ExportColumn::make('user.bloodGroup.name'),
            ExportColumn::make('user.gender.name'),
            ExportColumn::make('user.aadhaar_number'),
            ExportColumn::make('user.mother_tongue'),
            ExportColumn::make('user.date_of_birth'),
            ExportColumn::make('user.place_of_birth'),
            ExportColumn::make('user.notes'),
            ExportColumn::make('user.termination_date'),
            //
            ExportColumn::make('registration_id'),
            ExportColumn::make('quota.name'),
            ExportColumn::make('admission_number'),
            ExportColumn::make('current_status'),
            ExportColumn::make('tc_status'),
            ExportColumn::make('leaving_date'),
            ExportColumn::make('exit_reason'),
            ExportColumn::make('local_guardian_user_id'),
            ExportColumn::make('local_guardian_relationship'),
            ExportColumn::make('createdBy.name'),
            ExportColumn::make('updatedBy.name'),
            ExportColumn::make('deletedBy.name'),
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
