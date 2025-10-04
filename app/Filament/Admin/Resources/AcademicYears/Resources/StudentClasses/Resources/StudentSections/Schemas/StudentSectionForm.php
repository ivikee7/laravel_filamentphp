<?php

namespace App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\Schemas;

use App\Models\Room;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StudentSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('room_id')
                    ->relationship('room', 'name', function ($query, $get) {

                        $query->whereDoesntHave('studentSections');

                        // Ensure the current room_id is always included in the dropdown
                        if ($get('room_id')) {

                            $query->orWhere('id', $get('room_id'));
                        }

                    })
                    ->required(),
                Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->default(null),
            ]);
    }
}
