<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Pages\IDCard;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\Admin\Resources\Users\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
            // custom
            Action::make('id-card')
                ->url(fn (User $record): string => IDCard::getUrl(['record' => $record]))
                ->visible(fn (): bool => Auth::user()->can('view Attendance'))
                ->icon('heroicon-o-identification')
                ->color('info'),
        ];
    }
}
