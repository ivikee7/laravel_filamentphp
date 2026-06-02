<?php

namespace App\Filament\Admin\Resources\StudentFeeProfiles;

use App\Filament\Admin\Resources\StudentFeeProfiles\Pages;
use App\Models\StudentFeeProfile;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class StudentFeeProfileResource extends Resource
{
    protected static ?string $model = StudentFeeProfile::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::UserCircle;

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
            Select::make('fee_structure_id')
                ->relationship('structure', 'name')
                ->required()
                ->searchable()
                ->preload(),
            Select::make('scholarship_type')
                ->options([
                    'none' => 'None',
                    'percent' => 'Percent',
                    'fixed' => 'Fixed',
                ])
                ->default('none')
                ->required(),
            TextInput::make('scholarship_value')->numeric()->default(0)->required(),
            TextInput::make('sibling_discount_percent')->numeric()->default(0),
            Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.user.name')->label('Student')->searchable()->sortable(),
                TextColumn::make('student.admission_number')->label('Admission #')->searchable(),
                TextColumn::make('structure.name')->label('Structure')->searchable(),
                TextColumn::make('scholarship_type')->badge(),
                TextColumn::make('scholarship_value')->sortable(),
                TextColumn::make('sibling_discount_percent')->label('Sibling %')->sortable(),
                ToggleColumn::make('is_active'),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudentFeeProfiles::route('/'),
            'create' => Pages\CreateStudentFeeProfile::route('/create'),
            'edit' => Pages\EditStudentFeeProfile::route('/{record}/edit'),
        ];
    }
}

