<?php

namespace App\Filament\Admin\Resources\AcademicYears;

use App\Filament\Admin\Resources\AcademicYears\Pages\CreateAcademicYear;
use App\Filament\Admin\Resources\AcademicYears\Pages\EditAcademicYear;
use App\Filament\Admin\Resources\AcademicYears\Pages\ListAcademicYears;
use App\Filament\Admin\Resources\AcademicYears\Pages\ViewAcademicYear;
use App\Filament\Admin\Resources\AcademicYears\RelationManagers\StudentClassesRelationManager;
use App\Filament\Admin\Resources\AcademicYears\RelationManagers\StudentClassRelationManager;
use App\Filament\Admin\Resources\AcademicYears\Schemas\AcademicYearForm;
use App\Filament\Admin\Resources\AcademicYears\Schemas\AcademicYearInfolist;
use App\Filament\Admin\Resources\AcademicYears\Tables\AcademicYearsTable;
use App\Models\AcademicYear;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class AcademicYearResource extends Resource
{
    protected static ?string $model = AcademicYear::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = "School Management System";

    public static function form(Schema $schema): Schema
    {
        return AcademicYearForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AcademicYearInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AcademicYearsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            StudentClassesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAcademicYears::route('/'),
            'create' => CreateAcademicYear::route('/create'),
            'view' => ViewAcademicYear::route('/{record}'),
            'edit' => EditAcademicYear::route('/{record}/edit'),
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
