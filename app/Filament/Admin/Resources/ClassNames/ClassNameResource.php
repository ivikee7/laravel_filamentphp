<?php

namespace App\Filament\Admin\Resources\ClassNames;

use App\Filament\Admin\Resources\ClassNames\Pages\CreateClassName;
use App\Filament\Admin\Resources\ClassNames\Pages\EditClassName;
use App\Filament\Admin\Resources\ClassNames\Pages\ListClassNames;
use App\Filament\Admin\Resources\ClassNames\Pages\ViewClassName;
use App\Filament\Admin\Resources\ClassNames\Schemas\ClassNameForm;
use App\Filament\Admin\Resources\ClassNames\Schemas\ClassNameInfolist;
use App\Filament\Admin\Resources\ClassNames\Tables\ClassNamesTable;
use App\Models\ClassName;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassNameResource extends Resource
{
    protected static ?string $model = ClassName::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ClassNameForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ClassNameInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClassNamesTable::configure($table);
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
            'index' => ListClassNames::route('/'),
            'create' => CreateClassName::route('/create'),
            'view' => ViewClassName::route('/{record}'),
            'edit' => EditClassName::route('/{record}/edit'),
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
