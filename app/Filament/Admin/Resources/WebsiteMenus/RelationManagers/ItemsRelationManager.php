<?php

namespace App\Filament\Admin\Resources\WebsiteMenus\RelationManagers;

use App\Filament\Admin\Resources\WebsiteMenus\Resources\WebsiteMenuItems\WebsiteMenuItemResource;
use App\Filament\Admin\Resources\WebsiteMenus\Resources\WebsiteMenuItems\Tables\WebsiteMenuItemsTable;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $relatedResource = WebsiteMenuItemResource::class;

    public function table(Table $table): Table
    {
        return WebsiteMenuItemsTable::configure($table)
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['website_menu_id'] = $this->ownerRecord->getKey();

                        return $data;
                    }),
            ]);
    }
}
