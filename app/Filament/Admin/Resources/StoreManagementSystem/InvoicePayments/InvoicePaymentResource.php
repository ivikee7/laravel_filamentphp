<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\InvoicePayments;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Admin\Resources\StoreManagementSystem\InvoicePayments\Pages\ListInvoicePayments;
use App\Filament\Admin\Resources\StoreManagementSystem\InvoicePayments\Pages\CreateInvoicePayment;
use App\Filament\Admin\Resources\StoreManagementSystem\InvoicePayments\Pages\EditInvoicePayment;
use App\Filament\Admin\Resources\StoreManagementSystem\InvoicePaymentResource\Pages;
use App\Filament\Admin\Resources\StoreManagementSystem\InvoicePaymentResource\RelationManagers;
use App\Models\InvoicePayment;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoicePaymentResource extends Resource
{
    protected static ?string $model = InvoicePayment::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'Store Management System';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInvoicePayments::route('/'),
            'create' => CreateInvoicePayment::route('/create'),
            'edit' => EditInvoicePayment::route('/{record}/edit'),
        ];
    }
}
