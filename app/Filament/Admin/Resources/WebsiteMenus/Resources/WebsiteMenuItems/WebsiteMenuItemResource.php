<?php

namespace App\Filament\Admin\Resources\WebsiteMenus\Resources\WebsiteMenuItems;

use App\Filament\Admin\Resources\WebsiteMenus\Resources\WebsiteMenuItems\Pages\CreateWebsiteMenuItem;
use App\Filament\Admin\Resources\WebsiteMenus\Resources\WebsiteMenuItems\Pages\EditWebsiteMenuItem;
use App\Filament\Admin\Resources\WebsiteMenus\Resources\WebsiteMenuItems\Schemas\WebsiteMenuItemForm;
use App\Filament\Admin\Resources\WebsiteMenus\Resources\WebsiteMenuItems\Tables\WebsiteMenuItemsTable;
use App\Filament\Admin\Resources\WebsiteMenus\WebsiteMenuResource;
use App\Models\WebsiteMenuItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WebsiteMenuItemResource extends Resource
{
    protected static ?string $model = WebsiteMenuItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = WebsiteMenuResource::class;

    public static function form(Schema $schema): Schema
    {
        return WebsiteMenuItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WebsiteMenuItemsTable::configure($table);
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
            'create' => CreateWebsiteMenuItem::route('/create'),
            'edit' => EditWebsiteMenuItem::route('/{record}/edit'),
        ];
    }
}
