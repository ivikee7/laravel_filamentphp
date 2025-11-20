<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\Invoices;

use App\Models\StoreInvoice;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Admin\Resources\StoreManagementSystem\Invoices\RelationManagers\PaymentsRelationManager;
use App\Filament\Admin\Resources\StoreManagementSystem\Invoices\Pages\ListInvoices;
use App\Filament\Admin\Resources\StoreManagementSystem\Invoices\Pages\CreateInvoice;
use App\Filament\Admin\Resources\StoreManagementSystem\Invoices\Pages\ViewInvoice;
use App\Filament\Admin\Resources\StoreManagementSystem\Invoices\Pages\EditInvoice;
use App\Filament\Admin\Resources\StoreManagementSystem\InvoiceResource\Pages;
use App\Filament\Admin\Resources\StoreManagementSystem\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = StoreInvoice::class;

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
                TextColumn::make('id'),
                TextColumn::make('invoice_number'),
                TextColumn::make('user_id'),
                TextColumn::make('user.name'),
                TextColumn::make('sub_total'),
                TextColumn::make('discount'),
                TextColumn::make('total'),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
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
            PaymentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInvoices::route('/'),
            'create' => CreateInvoice::route('/create'),
            'view' => ViewInvoice::route('/{record}'),
            'edit' => EditInvoice::route('/{record}/edit'),
        ];
    }
}
