<?php

namespace App\Filament\Exports;

use App\Models\Registration;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class RegistrationExporter extends Exporter
{
    protected static ?string $model = Registration::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name'),
            ExportColumn::make('date_of_birth'),
            ExportColumn::make('gender.name'),
            ExportColumn::make('father_name'),
            ExportColumn::make('father_qualification'),
            ExportColumn::make('father_occupation'),
            ExportColumn::make('primary_contact_number'),
            ExportColumn::make('mother_name'),
            ExportColumn::make('mother_qualification'),
            ExportColumn::make('mother_occupation'),
            ExportColumn::make('secondary_contact_number'),
            ExportColumn::make('email'),
            ExportColumn::make('full_address')->label('Full Address')
                ->formatStateUsing(function ($state, $record) {
                    $parts = [];

                    if (!empty($record->address)) {
                        $parts[] = $record->address;
                    }
                    if (!empty($record->city)) {
                        $parts[] = $record->city;
                    }
                    if (!empty($record->state)) {
                        $parts[] = $record->state;
                    }
                    if (!empty($record->pin_code)) {
                        $parts[] = $record->pin_code;
                    }

                    return implode(', ', $parts);
                }),
            ExportColumn::make('previous_school'),
            ExportColumn::make('payment_amount'),
            ExportColumn::make('payment_mode'),
            ExportColumn::make('payment_notes'),
            ExportColumn::make('class.name'),
            ExportColumn::make('academicYear.name'),
            ExportColumn::make('admissionClass.className.name'),
            ExportColumn::make('enquiry_id'),
            ExportColumn::make('placement_test_date'),
            ExportColumn::make('placement_test_status'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your registration export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
