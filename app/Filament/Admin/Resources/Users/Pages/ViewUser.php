<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Pages\IDCard;
use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use App\Filament\Admin\Resources\Users\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('id-card')
                ->url(fn(User $record): string => IDCard::getUrl(['record' => $record]))
                ->visible(fn(): bool => Auth::user()->can('view Attendance'))
                ->icon('heroicon-o-identification')
                ->color('info'),
            Action::make('view-user-attendance-report')
                ->url(fn(User $record): string => UserResource::getUrl('view-user-attendance-report', ['record' => $record])),
        ];
    }
}
