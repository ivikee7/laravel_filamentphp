<?php

namespace App\Filament\Admin\Resources\Library\BookAuthors\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\Admin\Resources\Library\BookAuthors\BookAuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookAuthor extends EditRecord
{
    protected static string $resource = BookAuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
