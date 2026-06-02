<?php

namespace App\Filament\Admin\Resources\Exams;

use App\Filament\Admin\Resources\Exams\Pages\CreateExam;
use App\Filament\Admin\Resources\Exams\Pages\EditExam;
use App\Filament\Admin\Resources\Exams\Pages\ListExams;
use App\Filament\Admin\Resources\Exams\Pages\ViewExam;
use App\Filament\Admin\Resources\Exams\RelationManagers\QuestionsRelationManager;
use App\Filament\Admin\Resources\Exams\RelationManagers\ResultsRelationManager;
use App\Filament\Admin\Resources\Exams\RelationManagers\SubmissionsRelationManager;
use App\Filament\Admin\Resources\Exams\Schemas\ExamForm;
use App\Filament\Admin\Resources\Exams\Schemas\ExamInfolist;
use App\Filament\Admin\Resources\Exams\Tables\ExamsTable;
use App\Models\Exam;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    protected static string|\UnitEnum|null $navigationGroup = 'LMS';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ExamForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ExamInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExamsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            QuestionsRelationManager::class,
            SubmissionsRelationManager::class,
            ResultsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListExams::route('/'),
            'create' => CreateExam::route('/create'),
            'view'   => ViewExam::route('/{record}'),
            'edit'   => EditExam::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['examType']);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Exam::count();
    }
}

