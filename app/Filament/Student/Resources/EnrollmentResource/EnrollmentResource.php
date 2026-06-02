<?php

namespace App\Filament\Student\Resources\EnrollmentResource;

use App\Filament\Student\Resources\EnrollmentResource\Pages;
use App\Filament\Student\Resources\Schemas\EnrollmentInfolist;
use App\Models\CourseEnrollment;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EnrollmentResource extends Resource
{
    protected static ?string $model = CourseEnrollment::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    protected static ?string $navigationLabel = 'My Enrollments';
    protected static ?string $modelLabel = 'Enrollment';
    protected static ?string $pluralModelLabel = 'My Enrollments';
    protected static ?int $navigationSort = 2;
    protected static ?string $slug = 'enrollments';

    public static function getEloquentQuery(): Builder
    {
        $student = auth()->user()?->student;

        if (! $student) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->where('student_id', $student->id)
            ->with(['course']);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EnrollmentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst($state) : 'N/A')
                    ->color(fn (?string $state): string => match ($state) {
                        'active' => 'success',
                        'completed' => 'info',
                        'dropped' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('enrolled_at')
                    ->label('Enrolled At')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->defaultSort('enrolled_at', 'desc')
            ->recordUrl(fn (CourseEnrollment $record): string => Pages\ViewEnrollment::getUrl(['record' => $record]))
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->icon(Heroicon::ArrowRightCircle)
                    ->url(fn (CourseEnrollment $record): string => Pages\ViewEnrollment::getUrl(['record' => $record])),
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
            'index' => Pages\ListEnrollments::route('/'),
            'view' => Pages\ViewEnrollment::route('/{record}'),
        ];
    }
}

