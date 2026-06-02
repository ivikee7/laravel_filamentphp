<?php

namespace App\Filament\Student\Resources\CourseLessonResource;

use App\Filament\Student\Resources\CourseLessonResource\Pages;
use App\Filament\Student\Resources\Schemas\CourseLessonInfolist;
use App\Models\CourseLesson;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CourseLessonResource extends Resource
{
    protected static ?string $model = CourseLesson::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    protected static ?string $navigationLabel = 'Course Lessons';
    protected static ?string $modelLabel = 'Lesson';
    protected static ?string $pluralModelLabel = 'Course Lessons';
    protected static ?int $navigationSort = 4;
    protected static ?string $slug = 'lessons';

    public static function getEloquentQuery(): Builder
    {
        $student = auth()->user()?->student;

        if (! $student) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->where('is_published', true)
            ->whereHas('course.enrollments', fn (Builder $query) =>
                $query->where('student_id', $student->id)->where('status', 'active')
            )
            ->with(['course']);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CourseLessonInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Lesson')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('duration_minutes')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state): string => $state ? "{$state} min" : 'N/A')
                    ->sortable(),
                TextColumn::make('is_published')
                    ->label('Published')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Published' : 'Draft')
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning'),
            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(fn (CourseLesson $record): string => Pages\ViewCourseLesson::getUrl(['record' => $record]))
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->icon(Heroicon::ArrowRightCircle)
                    ->url(fn (CourseLesson $record): string => Pages\ViewCourseLesson::getUrl(['record' => $record])),
            ])
            ->toolbarActions([]);
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
            'index' => Pages\ListCourseLessons::route('/'),
            'view' => Pages\ViewCourseLesson::route('/{record}'),
        ];
    }
}

