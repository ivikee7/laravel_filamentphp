<?php

namespace App\Filament\Admin\Resources\RegistrationResource\Pages;

use App\Filament\Admin\Resources\RegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegistration extends EditRecord
{
    protected static string $resource = RegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\Action::make('print')
                ->label('Print Record')
                ->color('info')
                ->icon('heroicon-o-printer')
                ->url(fn ($record) => RegistrationResource::getUrl('print', ['record' => $record]))
                ->openUrlInNewTab(), // Opens the print page in a new tab
        ];
    }
}
