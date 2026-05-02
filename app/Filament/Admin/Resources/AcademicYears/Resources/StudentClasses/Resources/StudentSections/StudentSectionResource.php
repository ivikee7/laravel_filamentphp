<?php

namespace App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections;

use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\Pages\CreateStudentSection;
use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\Pages\EditStudentSection;
use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\Pages\ViewStudentSection;
use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\RelationManagers\StudentsRelationManager;
use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\Schemas\StudentSectionForm;
use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\Schemas\StudentSectionInfolist;
use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\Tables\StudentSectionsTable;
use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\StudentClassResource;
use App\Models\StudentSection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentSectionResource extends Resource
{
    protected static ?string $model = StudentSection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = StudentClassResource::class;

    protected static ?string $modelLabel = 'Section';

    public static function form(Schema $schema): Schema
    {
        return StudentSectionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StudentSectionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentSectionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            StudentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateStudentSection::route('/create'),
            'view' => ViewStudentSection::route('/{record}'),
            'edit' => EditStudentSection::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
