<?php

namespace App\Filament\Admin\Resources\Library\LibraryBookLanguages;

use App\Filament\Admin\Resources\Library\LibraryBookLanguages\Pages\CreateLibraryBookLanguage;
use App\Filament\Admin\Resources\Library\LibraryBookLanguages\Pages\EditLibraryBookLanguage;
use App\Filament\Admin\Resources\Library\LibraryBookLanguages\Pages\ListLibraryBookLanguages;
use App\Filament\Admin\Resources\Library\LibraryBookLanguages\Pages\ViewLibraryBookLanguage;
use App\Filament\Admin\Resources\Library\LibraryBookLanguages\Schemas\LibraryBookLanguageForm;
use App\Filament\Admin\Resources\Library\LibraryBookLanguages\Schemas\LibraryBookLanguageInfolist;
use App\Filament\Admin\Resources\Library\LibraryBookLanguages\Tables\LibraryBookLanguagesTable;
use App\Models\LibraryBookLanguage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LibraryBookLanguageResource extends Resource
{
    protected static ?string $model = LibraryBookLanguage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Library Management System';
    protected static ?string $label = 'Language';

    public static function form(Schema $schema): Schema
    {
        return LibraryBookLanguageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LibraryBookLanguageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LibraryBookLanguagesTable::configure($table);
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
            'index' => ListLibraryBookLanguages::route('/'),
            'create' => CreateLibraryBookLanguage::route('/create'),
            'view' => ViewLibraryBookLanguage::route('/{record}'),
            'edit' => EditLibraryBookLanguage::route('/{record}/edit'),
        ];
    }
}
