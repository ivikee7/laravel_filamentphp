<?php

namespace App\Filament\Admin\Resources\FeeTransactions;

use App\Filament\Admin\Resources\FeeTransactions\Pages;
use App\Models\FeeTransaction;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FeeTransactionResource extends Resource
{
    protected static ?string $model = FeeTransaction::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::CreditCard;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('fee_invoice_id')->relationship('invoice', 'invoice_no')->required()->searchable()->preload(),
            Select::make('student_id')
                ->relationship('student', 'admission_number')
                ->getOptionLabelFromRecordUsing(fn ($record) => ($record->user?->name ?? 'Student') . ' (' . ($record->admission_number ?? 'N/A') . ')')
                ->required()
                ->searchable()
                ->preload(),
            TextInput::make('amount')->required()->numeric()->minValue(1),
            TextInput::make('method')->required(),
            Select::make('status')
                ->required()
                ->options([
                    'pending' => 'Pending',
                    'success' => 'Success',
                    'failed' => 'Failed',
                    'refunded' => 'Refunded',
                ])
                ->default('success'),
            TextInput::make('reference'),
            DateTimePicker::make('payment_date')->required()->default(now()),
            Textarea::make('note')->rows(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice.invoice_no')->label('Invoice #')->searchable(),
                TextColumn::make('student.user.name')->label('Student')->searchable(),
                TextColumn::make('amount')->money('INR')->sortable(),
                TextColumn::make('method')->badge(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('reference')->toggleable(),
                TextColumn::make('payment_date')->dateTime()->sortable(),
            ])
            ->recordActions([
                Action::make('print_receipt')
                    ->label('Print Receipt')
                    ->icon('heroicon-o-printer')
                    ->url(fn (FeeTransaction $record): string => Pages\PrintFeeTransactionReceipt::getUrl(['record' => $record]))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('payment_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeeTransactions::route('/'),
            'create' => Pages\CreateFeeTransaction::route('/create'),
            'edit' => Pages\EditFeeTransaction::route('/{record}/edit'),
            'print' => Pages\PrintFeeTransactionReceipt::route('/{record}/print'),
        ];
    }
}

