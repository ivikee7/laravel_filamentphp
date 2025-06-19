<?php

namespace App\Filament\Admin\Resources\RegistrationResource\Pages;

use App\Filament\Admin\Resources\RegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRegistration extends ViewRecord
{
    protected static string $resource = RegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->url(fn ($record) => route('filament.admin.resources.registrations.view', ['record' => $record->id, 'print' => true]))
                ->openUrlInNewTab()
                ->extraAttributes([
                    'onclick' => 'window.print(); return false;',
                ]),
        ];
    }


}
