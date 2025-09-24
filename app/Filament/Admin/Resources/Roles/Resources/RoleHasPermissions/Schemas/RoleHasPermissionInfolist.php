<?php

namespace App\Filament\Admin\Resources\Roles\Resources\RoleHasPermissions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RoleHasPermissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('permission_id')
                    ->numeric(),
                TextEntry::make('role_id')
                    ->numeric(),
            ]);
    }
}
