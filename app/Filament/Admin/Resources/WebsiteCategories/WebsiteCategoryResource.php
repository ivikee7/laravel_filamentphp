<?php

namespace App\Filament\Admin\Resources\WebsiteCategories;

use App\Filament\Admin\Resources\WebsiteCategories\Pages\CreateWebsiteCategory;
use App\Filament\Admin\Resources\WebsiteCategories\Pages\EditWebsiteCategory;
use App\Filament\Admin\Resources\WebsiteCategories\Pages\ListWebsiteCategories;
use App\Filament\Admin\Resources\WebsiteCategories\Schemas\WebsiteCategoryForm;
use App\Filament\Admin\Resources\WebsiteCategories\Tables\WebsiteCategoriesTable;
use App\Models\WebsiteCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WebsiteCategoryResource extends Resource
{
    protected static ?string $model = WebsiteCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Website';

    protected static ?string $modelLabel = 'Categories';

    public static function form(Schema $schema): Schema
    {
        return WebsiteCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WebsiteCategoriesTable::configure($table);
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
            'index' => ListWebsiteCategories::route('/'),
            'create' => CreateWebsiteCategory::route('/create'),
            'edit' => EditWebsiteCategory::route('/{record}/edit'),
        ];
    }
}
