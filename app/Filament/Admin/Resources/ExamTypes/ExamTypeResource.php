<?php

namespace App\Filament\Admin\Resources\ExamTypes;

use App\Filament\Admin\Resources\ExamTypes\Pages\CreateExamType;
use App\Filament\Admin\Resources\ExamTypes\Pages\EditExamType;
use App\Filament\Admin\Resources\ExamTypes\Pages\ListExamTypes;
use App\Filament\Admin\Resources\ExamTypes\Pages\ViewExamType;
use App\Filament\Admin\Resources\ExamTypes\RelationManagers\ExamsRelationManager;
use App\Filament\Admin\Resources\ExamTypes\Schemas\ExamTypeForm;
use App\Filament\Admin\Resources\ExamTypes\Schemas\ExamTypeInfolist;
use App\Filament\Admin\Resources\ExamTypes\Tables\ExamTypesTable;
use App\Models\ExamType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExamTypeResource extends Resource
{
    protected static ?string $model = ExamType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    protected static string|\UnitEnum|null $navigationGroup = 'LMS';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Exam Types';

    public static function form(Schema $schema): Schema
    {
        return ExamTypeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ExamTypeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExamTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ExamsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExamTypes::route('/'),
            'create' => CreateExamType::route('/create'),
            'view' => ViewExamType::route('/{record}'),
            'edit' => EditExamType::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) ExamType::count();
    }
}
