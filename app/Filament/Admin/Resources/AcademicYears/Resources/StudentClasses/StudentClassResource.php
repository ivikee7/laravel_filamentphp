<?php

namespace App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses;

use App\Filament\Admin\Resources\AcademicYears\AcademicYearResource;
use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Pages\CreateStudentClass;
use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Pages\EditStudentClass;
use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Pages\ViewStudentClass;
use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Schemas\StudentClassForm;
use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Schemas\StudentClassInfolist;
use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Tables\StudentClassesTable;
use App\Filament\Admin\Resources\StudentClasses\RelationManagers\SectionsRelationManager;
use App\Models\StudentClass;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentClassResource extends Resource
{
    protected static ?string $model = StudentClass::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = AcademicYearResource::class;

    protected static ?string $modelLabel = 'Class';

    public static function form(Schema $schema): Schema
    {
        return StudentClassForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StudentClassInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentClassesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SectionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateStudentClass::route('/create'),
            'view' => ViewStudentClass::route('/{record}'),
            'edit' => EditStudentClass::route('/{record}/edit'),
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
