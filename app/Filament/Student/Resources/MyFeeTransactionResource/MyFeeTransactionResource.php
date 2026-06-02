<?php

namespace App\Filament\Student\Resources\MyFeeTransactionResource;

use App\Filament\Student\Resources\MyFeeTransactionResource\Pages;
use App\Models\FeeTransaction;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MyFeeTransactionResource extends Resource
{
    protected static ?string $model = FeeTransaction::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::ReceiptPercent;

    protected static ?string $navigationLabel = 'Fee Transactions';
    protected static ?int $navigationSort = 8;
    protected static ?string $slug = 'fee-transactions';

    public static function getEloquentQuery(): Builder
    {
        $student = auth()->user()?->student;

        if (! $student) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()->where('student_id', $student->id)->with(['invoice']);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice.invoice_no')->label('Invoice #')->searchable(),
                TextColumn::make('amount')->money('INR')->sortable(),
                TextColumn::make('method')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('reference')->placeholder('N/A'),
                TextColumn::make('payment_date')->dateTime()->sortable(),
            ])
            ->recordActions([
                Action::make('print_receipt')
                    ->label('Print Receipt')
                    ->icon('heroicon-o-printer')
                    ->url(fn (FeeTransaction $record): string => Pages\PrintMyFeeTransactionReceipt::getUrl(['record' => $record]))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('payment_date', 'desc');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyFeeTransactions::route('/'),
            'print' => Pages\PrintMyFeeTransactionReceipt::route('/{record}/print'),
        ];
    }
}

