<?php

namespace App\Filament\Admin\Resources\WebsiteSettings;

use App\Filament\Admin\Resources\WebsiteSettings\Pages\CreateWebsiteSettings;
use App\Filament\Admin\Resources\WebsiteSettings\Pages\EditWebsiteSettings;
use App\Filament\Admin\Resources\WebsiteSettings\Pages\ListWebsiteSettings;
use App\Filament\Admin\Resources\WebsiteSettings\Schemas\WebsiteSettingsForm;
use App\Filament\Admin\Resources\WebsiteSettings\Tables\WebsiteSettingsTable;
use App\Models\WebsiteSettings;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
class WebsiteSettingsResource extends Resource
{
    protected static ?string $model = WebsiteSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Website';

    protected static ?string $modelLabel = 'Settings';

    public static function form(Schema $schema): Schema
    {
        return WebsiteSettingsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WebsiteSettingsTable::configure($table);
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
            'index' => ListWebsiteSettings::route('/'),
            'create' => CreateWebsiteSettings::route('/create'),
            'edit' => EditWebsiteSettings::route('/{record}/edit'),
        ];
    }
}
