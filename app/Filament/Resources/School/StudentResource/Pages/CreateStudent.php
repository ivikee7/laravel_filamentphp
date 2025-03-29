<?php

namespace App\Filament\Resources\School\StudentResource\Pages;

use App\Filament\Resources\School\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return static::getModel()::create($data)->assignRole('Student');
    }
}
