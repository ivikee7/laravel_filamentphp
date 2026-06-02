<?php

namespace App\Filament\Student\Resources\MyFeeInvoiceResource;

use App\Filament\Student\Resources\MyFeeInvoiceResource\Pages;
use App\Models\FeeInvoice;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MyFeeInvoiceResource extends Resource
{
    protected static ?string $model = FeeInvoice::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::Banknotes;

    protected static ?string $navigationLabel = 'My Fees';
    protected static ?string $modelLabel = 'Fee Invoice';
    protected static ?string $pluralModelLabel = 'My Fees';
    protected static ?int $navigationSort = 7;
    protected static ?string $slug = 'fees';

    public static function getEloquentQuery(): Builder
    {
        $student = auth()->user()?->student;

        if (! $student) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()->where('student_id', $student->id);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Invoice')
                ->schema([
                    TextEntry::make('invoice_no')->label('Invoice #'),
                    TextEntry::make('period_start')->date(),
                    TextEntry::make('period_end')->date(),
                    TextEntry::make('due_date')->date(),
                    TextEntry::make('status')->badge(),
                    TextEntry::make('total_due')->money('INR'),
                    TextEntry::make('total_paid')->money('INR'),
                    TextEntry::make('late_fee')->money('INR'),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_no')->label('Invoice #')->searchable()->weight('bold'),
                TextColumn::make('period_start')->date(),
                TextColumn::make('period_end')->date(),
                TextColumn::make('due_date')->date(),
                TextColumn::make('total_due')->money('INR')->sortable(),
                TextColumn::make('total_paid')->money('INR')->sortable(),
                TextColumn::make('status')->badge()->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(fn (FeeInvoice $record): string => Pages\ViewMyFeeInvoice::getUrl(['record' => $record]));
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
            'index' => Pages\ListMyFeeInvoices::route('/'),
            'view' => Pages\ViewMyFeeInvoice::route('/{record}'),
        ];
    }
}

