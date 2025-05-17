<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem;

use App\Filament\Admin\Resources\StoreManagementSystem\InvoicePaymentResource\Pages;
use App\Filament\Admin\Resources\StoreManagementSystem\InvoicePaymentResource\RelationManagers;
use App\Models\InvoicePayment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoicePaymentResource extends Resource
{
    protected static ?string $model = InvoicePayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Store Management System';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListInvoicePayments::route('/'),
            'create' => Pages\CreateInvoicePayment::route('/create'),
            'edit' => Pages\EditInvoicePayment::route('/{record}/edit'),
        ];
    }
}
