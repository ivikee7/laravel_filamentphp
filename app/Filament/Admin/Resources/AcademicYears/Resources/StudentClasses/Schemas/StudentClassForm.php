<?php

namespace App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StudentClassForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('class_name_id')
                    ->relationship('className', 'name')
                    ->required(),
            ]);
    }
}
