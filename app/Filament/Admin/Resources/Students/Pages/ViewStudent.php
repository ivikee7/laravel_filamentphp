<?php

namespace App\Filament\Admin\Resources\Students\Pages;

use App\Filament\Admin\Pages\IDCard;
use App\Filament\Admin\Resources\Students\StudentResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewStudent extends ViewRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('id-card')
                ->url(fn (User $record): string => IDCard::getUrl(['record' => $record]))
                ->visible(fn (): bool => Auth::user()->can('view Attendance'))
                ->icon('heroicon-o-identification')
                ->color('info'),
        ];
    }
}
