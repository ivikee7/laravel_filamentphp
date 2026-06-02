<?php

namespace App\Filament\Admin\Resources\WebsiteTags;

use App\Filament\Admin\Resources\WebsiteTags\Pages\CreateWebsiteTag;
use App\Filament\Admin\Resources\WebsiteTags\Pages\EditWebsiteTag;
use App\Filament\Admin\Resources\WebsiteTags\Pages\ListWebsiteTags;
use App\Filament\Admin\Resources\WebsiteTags\Schemas\WebsiteTagForm;
use App\Filament\Admin\Resources\WebsiteTags\Tables\WebsiteTagsTable;
use App\Models\WebsiteTag;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WebsiteTagResource extends Resource
{
    protected static ?string $model = WebsiteTag::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Website';

    protected static ?string $modelLabel = 'Tag';

    public static function form(Schema $schema): Schema
    {
        return WebsiteTagForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WebsiteTagsTable::configure($table);
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
            'index' => ListWebsiteTags::route('/'),
            'create' => CreateWebsiteTag::route('/create'),
            'edit' => EditWebsiteTag::route('/{record}/edit'),
        ];
    }
}
