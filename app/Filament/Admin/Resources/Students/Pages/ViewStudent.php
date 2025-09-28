<?php

namespace App\Filament\Admin\Resources\Students\Pages;

use App\Filament\Admin\Pages\IDCard;
use App\Filament\Admin\Pages\IDCards\ViewIDCard;
use App\Models\User;
use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Students\StudentResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewStudent extends ViewRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
//            Action::make('id-card')
//                ->url(fn($record) => route(IDCard::getUrl(['record' => $record->id])))
//                ->visible(fn() => Auth::user()->can('view Attendance'))
        ];
    }
}
