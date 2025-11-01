<?php

namespace App\Filament\Admin\Resources\Library\LibraryBookClasses;

use App\Filament\Admin\Resources\Library\LibraryBookClasses\Pages\CreateLibraryBookClass;
use App\Filament\Admin\Resources\Library\LibraryBookClasses\Pages\EditLibraryBookClass;
use App\Filament\Admin\Resources\Library\LibraryBookClasses\Pages\ListLibraryBookClasses;
use App\Filament\Admin\Resources\Library\LibraryBookClasses\Pages\ViewLibraryBookClass;
use App\Filament\Admin\Resources\Library\LibraryBookClasses\Schemas\LibraryBookClassForm;
use App\Filament\Admin\Resources\Library\LibraryBookClasses\Schemas\LibraryBookClassInfolist;
use App\Filament\Admin\Resources\Library\LibraryBookClasses\Tables\LibraryBookClassesTable;
use App\Models\LibraryBookClass;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LibraryBookClassResource extends Resource
{
    protected static ?string $model = LibraryBookClass::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Library Management System';
    protected static ?string $label = 'Class';

    public static function form(Schema $schema): Schema
    {
        return LibraryBookClassForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LibraryBookClassInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LibraryBookClassesTable::configure($table);
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
            'index' => ListLibraryBookClasses::route('/'),
            'create' => CreateLibraryBookClass::route('/create'),
            'view' => ViewLibraryBookClass::route('/{record}'),
            'edit' => EditLibraryBookClass::route('/{record}/edit'),
        ];
    }
}
