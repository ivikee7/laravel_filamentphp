<?php

namespace App\Filament\Admin\Resources\WebsitePages;

use App\Filament\Admin\Resources\WebsitePages\Pages\CreateWebsitePage;
use App\Filament\Admin\Resources\WebsitePages\Pages\EditWebsitePage;
use App\Filament\Admin\Resources\WebsitePages\Pages\ListWebsitePages;
use App\Filament\Admin\Resources\WebsitePages\Schemas\WebsitePageForm;
use App\Filament\Admin\Resources\WebsitePages\Tables\WebsitePagesTable;
use App\Models\WebsitePage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WebsitePageResource extends Resource
{
    protected static ?string $model = WebsitePage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Website';

    protected static ?string $modelLabel = 'Page';

    public static function form(Schema $schema): Schema
    {
        return WebsitePageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WebsitePagesTable::configure($table);
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
            'index' => ListWebsitePages::route('/'),
            'create' => CreateWebsitePage::route('/create'),
            'edit' => EditWebsitePage::route('/{record}/edit'),
        ];
    }
}
