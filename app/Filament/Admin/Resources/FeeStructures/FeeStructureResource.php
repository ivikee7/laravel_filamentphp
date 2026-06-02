<?php

namespace App\Filament\Admin\Resources\FeeStructures;

use App\Filament\Admin\Resources\FeeStructures\Pages;
use App\Models\FeeStructure;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class FeeStructureResource extends Resource
{
    protected static ?string $model = FeeStructure::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(255),
            Select::make('academic_year_id')
                ->relationship('academicYear', 'name')
                ->searchable()
                ->preload(),
            Select::make('student_class_id')
                ->relationship('studentClass', 'name')
                ->searchable()
                ->preload(),
            Select::make('billing_cycle')
                ->options([
                    'monthly' => 'Monthly',
                    'quarterly' => 'Quarterly',
                    'term' => 'Term/Semester',
                    'one_time' => 'One-time',
                    'custom' => 'Custom',
                ])
                ->required()
                ->default('monthly'),
            TextInput::make('due_day')->numeric()->minValue(1)->maxValue(31),
            Toggle::make('is_active')->default(true),

            Repeater::make('items')
                ->relationship()
                ->schema([
                    Select::make('fee_head_id')
                        ->relationship('feeHead', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    TextInput::make('amount')->required()->numeric()->minValue(0),
                    Toggle::make('discountable')->default(true),
                    Toggle::make('is_optional')->default(false),
                ])
                ->columns(4)
                ->defaultItems(1)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                TextColumn::make('academicYear.name')->label('Academic Year')->placeholder('N/A'),
                TextColumn::make('studentClass.name')->label('Class')->placeholder('N/A'),
                TextColumn::make('billing_cycle')->badge(),
                TextColumn::make('items_count')->counts('items')->label('Items'),
                ToggleColumn::make('is_active'),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeeStructures::route('/'),
            'create' => Pages\CreateFeeStructure::route('/create'),
            'edit' => Pages\EditFeeStructure::route('/{record}/edit'),
        ];
    }
}

