<?php

namespace App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\Resources\StudentClassAssignments;

use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\Resources\StudentClassAssignments\Pages\CreateStudentClassAssignment;
use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\Resources\StudentClassAssignments\Pages\EditStudentClassAssignment;
use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\Resources\StudentClassAssignments\Schemas\StudentClassAssignmentForm;
use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\Resources\StudentClassAssignments\Tables\StudentClassAssignmentsTable;
use App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\StudentSectionResource;
use App\Filament\Admin\Resources\Students\StudentResource;
use App\Models\Student;
use App\Models\StudentClassAssignment;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentClassAssignmentResource extends Resource
{
    protected static ?string $model = StudentClassAssignment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = StudentSectionResource::class;

    protected static ?string $modelLabel = 'Students';

    public static function form(Schema $schema): Schema
    {
        return StudentClassAssignmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentClassAssignmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
//            'create' => CreateStudentClassAssignment::route('/create'),
//            'edit' => EditStudentClassAssignment::route('/{record}/edit'),
//            'edit' => StudentResource::getUrl('edit', ['record' => $record->student->user->id]),

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
