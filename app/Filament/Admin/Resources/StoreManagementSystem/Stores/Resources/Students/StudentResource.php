<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\Stores\Resources\Students;

use App\Filament\Admin\Resources\StoreManagementSystem\Stores\Resources\Students\Pages\CreateStudent;
use App\Filament\Admin\Resources\StoreManagementSystem\Stores\Resources\Students\Pages\EditStudent;
use App\Filament\Admin\Resources\StoreManagementSystem\Stores\Resources\Students\Pages\ViewStudent;
use App\Filament\Admin\Resources\StoreManagementSystem\Stores\Resources\Students\Schemas\StudentForm;
use App\Filament\Admin\Resources\StoreManagementSystem\Stores\Resources\Students\Schemas\StudentInfolist;
use App\Filament\Admin\Resources\StoreManagementSystem\Stores\Resources\Students\Tables\StudentsTable;
use App\Filament\Admin\Resources\StoreManagementSystem\Stores\StoreResource;
use App\Models\Student;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = StoreResource::class;

    public static function form(Schema $schema): Schema
    {
        return StudentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StudentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentsTable::configure($table);
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
            'create' => CreateStudent::route('/create'),
            'view' => ViewStudent::route('/{record}'),
            'edit' => EditStudent::route('/{record}/edit'),
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
