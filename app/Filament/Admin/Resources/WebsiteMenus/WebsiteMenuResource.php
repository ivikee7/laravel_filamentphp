<?php

namespace App\Filament\Admin\Resources\WebsiteMenus;

use App\Filament\Admin\Resources\WebsiteMenus\Pages\CreateWebsiteMenu;
use App\Filament\Admin\Resources\WebsiteMenus\Pages\EditWebsiteMenu;
use App\Filament\Admin\Resources\WebsiteMenus\Pages\ListWebsiteMenus;
use App\Filament\Admin\Resources\WebsiteMenus\Pages\ViewWebsiteMenu;
use App\Filament\Admin\Resources\WebsiteMenus\RelationManagers\ItemsRelationManager;
use App\Filament\Admin\Resources\WebsiteMenus\Schemas\WebsiteMenuForm;
use App\Filament\Admin\Resources\WebsiteMenus\Schemas\WebsiteMenuInfolist;
use App\Filament\Admin\Resources\WebsiteMenus\Tables\WebsiteMenusTable;
use App\Models\WebsiteMenu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WebsiteMenuResource extends Resource
{
    protected static ?string $model = WebsiteMenu::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Website';

    protected static ?string $modelLabel = 'Menu';

    public static function form(Schema $schema): Schema
    {
        return WebsiteMenuForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WebsiteMenuInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WebsiteMenusTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWebsiteMenus::route('/'),
            'create' => CreateWebsiteMenu::route('/create'),
            'view' => ViewWebsiteMenu::route('/{record}'),
            'edit' => EditWebsiteMenu::route('/{record}/edit'),
        ];
    }
}
