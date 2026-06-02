<?php

namespace App\Filament\Student\Resources\ExamResultResource;

use App\Filament\Student\Resources\ExamResultResource\Pages;
use App\Filament\Student\Resources\Schemas\ExamResultInfolist;
use App\Models\ExamResult;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ExamResultResource extends Resource
{
    protected static ?string $model = ExamResult::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    protected static ?string $navigationLabel = 'My Results';
    protected static ?string $modelLabel = 'Result';
    protected static ?string $pluralModelLabel = 'My Results';
    protected static ?int $navigationSort = 6;
    protected static ?string $slug = 'results';

    public static function getEloquentQuery(): Builder
    {
        $student = auth()->user()?->student;

        if (! $student) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->where('student_id', $student->id)
            ->with(['exam', 'exam.course', 'exam.examType']);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ExamResultInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('exam.title')
                    ->label('Exam')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('score')
                    ->label('Score')
                    ->sortable(),
                TextColumn::make('grade')
                    ->label('Grade')
                    ->badge()
                    ->placeholder('N/A')
                    ->color('info'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst($state) : 'N/A')
                    ->color(fn (?string $state): string => match ($state) {
                        'graded' => 'success',
                        'submitted' => 'info',
                        'pending' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(fn (ExamResult $record): string => Pages\ViewExamResult::getUrl(['record' => $record]))
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->icon(Heroicon::ArrowRightCircle)
                    ->url(fn (ExamResult $record): string => Pages\ViewExamResult::getUrl(['record' => $record])),
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
            'index' => Pages\ListExamResults::route('/'),
            'view' => Pages\ViewExamResult::route('/{record}'),
        ];
    }
}

