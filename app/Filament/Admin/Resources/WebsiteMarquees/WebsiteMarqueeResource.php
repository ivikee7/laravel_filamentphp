<?php

namespace App\Filament\Admin\Resources\WebsiteMarquees;

use App\Filament\Admin\Resources\WebsiteMarquees\Pages\CreateWebsiteMarquee;
use App\Filament\Admin\Resources\WebsiteMarquees\Pages\EditWebsiteMarquee;
use App\Filament\Admin\Resources\WebsiteMarquees\Pages\ListWebsiteMarquees;
use App\Filament\Admin\Resources\WebsiteMarquees\Schemas\WebsiteMarqueeForm;
use App\Filament\Admin\Resources\WebsiteMarquees\Tables\WebsiteMarqueesTable;
use App\Models\WebsiteMarquee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WebsiteMarqueeResource extends Resource
{
    protected static ?string $model = WebsiteMarquee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Website';

    protected static ?string $modelLabel = 'Marquee';

    public static function form(Schema $schema): Schema
    {
        return WebsiteMarqueeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WebsiteMarqueesTable::configure($table);
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
            'index' => ListWebsiteMarquees::route('/'),
            'create' => CreateWebsiteMarquee::route('/create'),
            'edit' => EditWebsiteMarquee::route('/{record}/edit'),
        ];
    }
}
