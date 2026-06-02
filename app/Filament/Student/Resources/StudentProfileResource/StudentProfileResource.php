<?php

namespace App\Filament\Student\Resources\StudentProfileResource;

use App\Filament\Student\Resources\Schemas\StudentProfileInfolist;
use App\Filament\Student\Resources\StudentProfileResource\Pages;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class StudentProfileResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?string $navigationLabel = 'My Profile';
    protected static ?string $modelLabel = 'Profile';
    protected static ?string $pluralModelLabel = 'My Profile';
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = 'profile';

    public static function getEloquentQuery(): Builder
    {
        $userId = auth()->id();

        if (! $userId) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->whereKey($userId)
            ->with([
                'gender',
                'bloodGroup',
                'gSuiteUser',
                'student',
                'student.quota',
                'student.classAssignment',
                'student.classAssignment.academicYear',
                'student.classAssignment.studentClass',
                'student.classAssignment.studentSection',
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StudentProfileInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->weight('bold'),
                TextColumn::make('email')
                    ->label('Email'),
                TextColumn::make('student.classAssignment.studentClass.name')
                    ->label('Class')
                    ->placeholder('N/A'),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Suspended')
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
            ])
            ->recordUrl(fn (User $record): string => Pages\ViewStudentProfile::getUrl(['record' => $record]))
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->icon(Heroicon::ArrowRightCircle)
                    ->url(fn (User $record): string => Pages\ViewStudentProfile::getUrl(['record' => $record])),
            ])
            ->toolbarActions([]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        return auth()->check();
    }

    public static function canView(Model $record): bool
    {
        return auth()->check() && (int) $record->id === (int) auth()->id();
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
            'index' => Pages\ListStudentProfiles::route('/'),
            'view' => Pages\ViewStudentProfile::route('/{record}'),
        ];
    }
}

