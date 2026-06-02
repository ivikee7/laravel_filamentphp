<?php

namespace App\Filament\Admin\Resources\WebsiteMarquees\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WebsiteMarqueeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
            ]);
    }
}
