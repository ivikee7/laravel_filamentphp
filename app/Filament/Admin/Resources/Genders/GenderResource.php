<?php

namespace App\Filament\Admin\Resources\Genders;

use App\Filament\Admin\Resources\Genders\Pages\CreateGender;
use App\Filament\Admin\Resources\Genders\Pages\EditGender;
use App\Filament\Admin\Resources\Genders\Pages\ListGenders;
use App\Filament\Admin\Resources\Genders\Pages\ViewGender;
use App\Filament\Admin\Resources\Genders\Schemas\GenderForm;
use App\Filament\Admin\Resources\Genders\Schemas\GenderInfolist;
use App\Filament\Admin\Resources\Genders\Tables\GendersTable;
use App\Models\Gender;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class GenderResource extends Resource
{
    protected static ?string $model = Gender::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = "User";

    public static function form(Schema $schema): Schema
    {
        return GenderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GenderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GendersTable::configure($table);
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
            'index' => ListGenders::route('/'),
            'create' => CreateGender::route('/create'),
            'view' => ViewGender::route('/{record}'),
            'edit' => EditGender::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return Gender::count();
    }
}
