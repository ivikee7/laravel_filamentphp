<?php

namespace App\Filament\Exports;

use App\Models\StoreInvoiceTransaction;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class StoreInvoiceTransactionExporter extends Exporter
{
    protected static ?string $model = StoreInvoiceTransaction::class;

    // Update columns to match Filament table columns requested by the user
    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('storeInvoice.id')->label('InvoiceId'),
            ExportColumn::make('storeInvoice.user.id')->label('User Id'),
            ExportColumn::make('storeInvoice.user.name')->label('Name'),
            ExportColumn::make('method')->label('Method'),
            ExportColumn::make('amount')->label('Amount'),
            ExportColumn::make('remarks')->label('Remarks'),
            ExportColumn::make('createdBy.name')->label('Created By'),
            ExportColumn::make('updatedBy.name')->label('Updated By'),
            ExportColumn::make('created_at')->label('Created At'),
            ExportColumn::make('updated_at')->label('Updated At'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your store invoice transaction export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
