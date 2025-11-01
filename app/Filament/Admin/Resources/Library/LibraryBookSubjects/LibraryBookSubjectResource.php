<?php

namespace App\Filament\Admin\Resources\Library\LibraryBookSubjects;

use App\Filament\Admin\Resources\Library\LibraryBookSubjects\Pages\CreateLibraryBookSubject;
use App\Filament\Admin\Resources\Library\LibraryBookSubjects\Pages\EditLibraryBookSubject;
use App\Filament\Admin\Resources\Library\LibraryBookSubjects\Pages\ListLibraryBookSubjects;
use App\Filament\Admin\Resources\Library\LibraryBookSubjects\Pages\ViewLibraryBookSubject;
use App\Filament\Admin\Resources\Library\LibraryBookSubjects\Schemas\LibraryBookSubjectForm;
use App\Filament\Admin\Resources\Library\LibraryBookSubjects\Schemas\LibraryBookSubjectInfolist;
use App\Filament\Admin\Resources\Library\LibraryBookSubjects\Tables\LibraryBookSubjectsTable;
use App\Models\LibraryBookSubject;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LibraryBookSubjectResource extends Resource
{
    protected static ?string $model = LibraryBookSubject::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Library Management System';
    protected static ?string $label = 'Subject';

    public static function form(Schema $schema): Schema
    {
        return LibraryBookSubjectForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LibraryBookSubjectInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LibraryBookSubjectsTable::configure($table);
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
            'index' => ListLibraryBookSubjects::route('/'),
            'create' => CreateLibraryBookSubject::route('/create'),
            'view' => ViewLibraryBookSubject::route('/{record}'),
            'edit' => EditLibraryBookSubject::route('/{record}/edit'),
        ];
    }
}
