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
            ExportColumn::make('user.id'),
            ExportColumn::make('registration_id'),
            ExportColumn::make('quota.name'),
            ExportColumn::make('admission_number'),
            ExportColumn::make('current_status'),
            ExportColumn::make('tc_status'),
            ExportColumn::make('leaving_date'),
            ExportColumn::make('exit_reason'),
            ExportColumn::make('local_guardian_user_id'),
            ExportColumn::make('local_guardian_relationship'),
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
