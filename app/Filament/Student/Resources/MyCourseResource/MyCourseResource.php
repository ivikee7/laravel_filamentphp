<?php

namespace App\Filament\Student\Resources\MyCourseResource;

use App\Filament\Student\Resources\MyCourseResource\Pages;
use App\Filament\Student\Resources\MyCourseResource\Schemas\CourseInfolist;
use App\Filament\Student\Resources\MyCourseResource\Tables\MyCoursesTable;
use App\Models\Course;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MyCourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    protected static ?string $navigationLabel = 'My Courses';
    protected static ?string $modelLabel = 'Course';
    protected static ?string $pluralModelLabel = 'My Courses';
    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        $student = auth()->user()?->student;

        if (! $student) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->whereHas('enrollments', fn (Builder $q) =>
                $q->where('student_id', $student->id)->where('status', 'active')
            )
            ->where('status', 'published')
            ->with(['subject', 'instructor', 'academicYear'])
            ->withCount(['lessons', 'materials', 'exams']);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CourseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MyCoursesTable::configure($table);
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
            'index' => Pages\ListMyCourses::route('/'),
            'view' => Pages\ViewMyCourse::route('/{record}'),
        ];
    }
}

