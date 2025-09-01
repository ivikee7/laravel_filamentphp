<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\Stores\RelationManagers;

use App\Filament\Admin\Resources\StoreManagementSystem\Stores\Resources\Students\StudentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    protected static ?string $relatedResource = StudentResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
