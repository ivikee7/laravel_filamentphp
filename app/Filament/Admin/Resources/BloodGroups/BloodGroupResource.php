<?php

namespace App\Filament\Admin\Resources\BloodGroups;

use App\Filament\Admin\Resources\BloodGroups\Pages\CreateBloodGroup;
use App\Filament\Admin\Resources\BloodGroups\Pages\EditBloodGroup;
use App\Filament\Admin\Resources\BloodGroups\Pages\ListBloodGroups;
use App\Filament\Admin\Resources\BloodGroups\Pages\ViewBloodGroup;
use App\Filament\Admin\Resources\BloodGroups\Schemas\BloodGroupForm;
use App\Filament\Admin\Resources\BloodGroups\Schemas\BloodGroupInfolist;
use App\Filament\Admin\Resources\BloodGroups\Tables\BloodGroupsTable;
use App\Models\BloodGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class BloodGroupResource extends Resource
{
    protected static ?string $model = BloodGroup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = "School Management System";

    public static function form(Schema $schema): Schema
    {
        return BloodGroupForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BloodGroupInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BloodGroupsTable::configure($table);
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
            'index' => ListBloodGroups::route('/'),
            'create' => CreateBloodGroup::route('/create'),
            'view' => ViewBloodGroup::route('/{record}'),
            'edit' => EditBloodGroup::route('/{record}/edit'),
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
        return BloodGroup::count();
    }
}
