<?php

namespace App\Filament\Exports;

use App\Models\StoreInvoice;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class StoreInvoiceExporter extends Exporter
{
    protected static ?string $model = StoreInvoice::class;

    public static function getColumns(): array
    {
        return [
            // Match the columns used in the Filament table view so exported files line up with the UI
            ExportColumn::make('id')->label('#'),
            ExportColumn::make('user.id')->label('User Id'),
            ExportColumn::make('user.name')->label('Name'),
            ExportColumn::make('user.father_name')->label('Father Name'),
            ExportColumn::make('user.mother_name')->label('Mother Name'),
            ExportColumn::make('class.name')->label('Class'),
            ExportColumn::make('subtotal_amount')->label('Subtotal'),
            ExportColumn::make('discount_amount')->label('Discount'),
            ExportColumn::make('total_amount')->label('Total'),
            // If your model provides accessors for paid/due amounts use them here
            ExportColumn::make('total_paid_amount')->label('Paid'),
            ExportColumn::make('total_due_amount')->label('Due'),
            ExportColumn::make('remarks')->label('Remarks'),
            ExportColumn::make('created_at')->label('Created At'),
            ExportColumn::make('createdBy.name')->label('Created By'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your store invoice export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
