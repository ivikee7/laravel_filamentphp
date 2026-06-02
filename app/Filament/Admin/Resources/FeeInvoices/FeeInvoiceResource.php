<?php

namespace App\Filament\Admin\Resources\FeeInvoices;

use App\Filament\Admin\Resources\FeeInvoices\Pages;
use App\Models\FeeInvoice;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FeeInvoiceResource extends Resource
{
    protected static ?string $model = FeeInvoice::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('student_id')
                ->relationship('student', 'admission_number')
                ->getOptionLabelFromRecordUsing(fn ($record) => ($record->user?->name ?? 'Student') . ' (' . ($record->admission_number ?? 'N/A') . ')')
                ->required()
                ->searchable()
                ->preload(),
            DatePicker::make('period_start')->required(),
            DatePicker::make('period_end')->required(),
            DatePicker::make('due_date')->required(),
            Textarea::make('notes')->rows(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_no')->label('Invoice #')->searchable()->sortable()->weight('bold'),
                TextColumn::make('student.user.name')->label('Student')->searchable()->sortable(),
                TextColumn::make('period_start')->date(),
                TextColumn::make('period_end')->date(),
                TextColumn::make('due_date')->date()->sortable(),
                TextColumn::make('total_due')->money('INR')->sortable(),
                TextColumn::make('total_paid')->money('INR')->sortable(),
                TextColumn::make('status')->badge()->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->recordActions([
                Action::make('view')
                    ->icon(Heroicon::Eye)
                    ->url(fn (FeeInvoice $record): string => Pages\ViewFeeInvoice::getUrl(['record' => $record])),
                Action::make('mark_late_fee')
                    ->label('Apply Late Fee')
                    ->icon(Heroicon::ExclamationTriangle)
                    ->visible(fn (FeeInvoice $record): bool => in_array($record->status, ['issued', 'partial', 'overdue'], true))
                    ->action(function (FeeInvoice $record): void {
                        app(\App\Services\FeeManagement\FeeEngine::class)->applyLateFee($record);
                    }),
            ]);
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeeInvoices::route('/'),
            'create' => Pages\CreateFeeInvoice::route('/create'),
            'view' => Pages\ViewFeeInvoice::route('/{record}'),
            'print' => Pages\PrintFeeInvoice::route('/{record}/print'),
        ];
    }
}

