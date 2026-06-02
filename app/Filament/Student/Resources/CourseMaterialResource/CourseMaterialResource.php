<?php

namespace App\Filament\Student\Resources\CourseMaterialResource;

use App\Filament\Student\Resources\CourseMaterialResource\Pages;
use App\Filament\Student\Resources\Schemas\CourseMaterialInfolist;
use App\Models\CourseMaterial;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CourseMaterialResource extends Resource
{
    protected static ?string $model = CourseMaterial::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    protected static ?string $navigationLabel = 'Course Materials';
    protected static ?string $modelLabel = 'Material';
    protected static ?string $pluralModelLabel = 'Course Materials';
    protected static ?int $navigationSort = 5;
    protected static ?string $slug = 'materials';

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
        return CourseMaterialInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Material')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst($state) : 'N/A')
                    ->color('info'),
                TextColumn::make('is_published')
                    ->label('Published')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Published' : 'Draft')
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning'),
            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(fn (CourseMaterial $record): string => Pages\ViewCourseMaterial::getUrl(['record' => $record]))
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->icon(Heroicon::ArrowRightCircle)
                    ->url(fn (CourseMaterial $record): string => Pages\ViewCourseMaterial::getUrl(['record' => $record])),
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
            'index' => Pages\ListCourseMaterials::route('/'),
            'view' => Pages\ViewCourseMaterial::route('/{record}'),
        ];
    }
}

