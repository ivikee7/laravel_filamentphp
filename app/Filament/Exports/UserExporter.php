<?php

namespace App\Filament\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Facades\Storage;

class UserExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('avatar')
                ->getStateUsing(fn($record) => $record->avatar ? Storage::disk('public')->url($record->avatar) : null),
            ExportColumn::make('name'),
            ExportColumn::make('email'),
            ExportColumn::make('official_email'),
            ExportColumn::make('father_name'),
            ExportColumn::make('mother_name'),
            ExportColumn::make('primary_contact_number'),
            ExportColumn::make('secondary_contact_number'),
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
            ExportColumn::make('avatar'),
            ExportColumn::make('is_active'),
            ExportColumn::make('bloodGroup.name'),
            ExportColumn::make('gender.name'),
            ExportColumn::make('aadhaar_number'),
            ExportColumn::make('mother_tongue'),
            ExportColumn::make('date_of_birth'),
            ExportColumn::make('place_of_birth'),
            ExportColumn::make('notes'),
            ExportColumn::make('termination_date'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your user export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
