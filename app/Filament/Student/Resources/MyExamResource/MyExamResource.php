<?php

namespace App\Filament\Student\Resources\MyExamResource;

use App\Filament\Student\Resources\MyExamResource\Pages;
use App\Filament\Student\Resources\MyExamResource\Pages\AttemptViewPage;
use App\Filament\Student\Resources\MyExamResource\Pages\ExamAttemptsPage;
use App\Filament\Student\Resources\MyExamResource\Schemas\ExamInfolist;
use App\Filament\Student\Resources\MyExamResource\Tables\MyExamsTable;
use App\Models\Exam;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MyExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    protected static ?string $navigationLabel = 'My Exams';
    protected static ?string $modelLabel = 'Exam';
    protected static ?string $pluralModelLabel = 'My Exams';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        $student = auth()->user()?->student;

        if (! $student) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        $enrolledCourseIds = $student->enrollments()
            ->where('status', 'active')
            ->pluck('course_id');

        return parent::getEloquentQuery()
            ->where('status', 'published')
            ->where(fn (Builder $q) => $q
                ->whereIn('course_id', $enrolledCourseIds)
                ->orWhereNull('course_id')
            )
            ->with([
                'course',
                'academicYear',
                'examType',
            ])
            ->withCount(['questions']);
    }


    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ExamInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MyExamsTable::configure($table);
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
            'index' => Pages\ListMyExams::route('/'),
            'take' => Pages\TakeExamPage::route('/{record}/take'),
            'attempt-view' => AttemptViewPage::route('/{record}/attempt-view'),
            'attempts' => ExamAttemptsPage::route('/{record}/attempts'),
        ];
    }
}

