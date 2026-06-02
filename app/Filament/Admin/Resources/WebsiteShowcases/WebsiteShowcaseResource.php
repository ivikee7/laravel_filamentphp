<?php

namespace App\Filament\Admin\Resources\WebsiteShowcases;

use App\Filament\Admin\Resources\WebsiteShowcases\Pages\CreateWebsiteShowcase;
use App\Filament\Admin\Resources\WebsiteShowcases\Pages\EditWebsiteShowcase;
use App\Filament\Admin\Resources\WebsiteShowcases\Pages\ListWebsiteShowcases;
use App\Filament\Admin\Resources\WebsiteShowcases\Schemas\WebsiteShowcaseForm;
use App\Filament\Admin\Resources\WebsiteShowcases\Tables\WebsiteShowcasesTable;
use App\Models\WebsiteShowcase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WebsiteShowcaseResource extends Resource
{
    protected static ?string $model = WebsiteShowcase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Website';

    protected static ?string $modelLabel = 'Showcase';

    public static function form(Schema $schema): Schema
    {
        return WebsiteShowcaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WebsiteShowcasesTable::configure($table);
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
            'index' => ListWebsiteShowcases::route('/'),
            'create' => CreateWebsiteShowcase::route('/create'),
            'edit' => EditWebsiteShowcase::route('/{record}/edit'),
        ];
    }
}
