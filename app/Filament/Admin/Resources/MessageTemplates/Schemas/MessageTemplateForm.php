<?php

namespace App\Filament\Admin\Resources\MessageTemplates\Schemas;

use App\Models\User;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MessageTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    Select::make('sms_provider_id')
                        ->relationship('smsProvider', 'name', function ($query) {
                            $query->where('is_active', true);
                            return $query;
                        }),
                    TextInput::make('name')
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->maxLength(255),
                    Textarea::make('content')
                        ->label('Template')
                        ->hint('Use placeholders like {{name}}, {{date}}')
                        ->reactive()
                        ->helperText(fn($state) => strlen($state) . ' / 160 characters') // Live count
                        ->required()
                        ->columnSpanFull(),
                    Repeater::make('variables')
                        ->schema([
                            TextInput::make('name')
                                ->label('Variable Name')
                                ->required(),

                            Select::make('column')
                                ->label('Mapped User Field')
                                ->options(array_combine(
                                    (new User())->getFillable(),
                                    (new User())->getFillable()
                                ))
                                ->searchable()
                                ->required(),
                        ])
                        ->label('Expected Variables')
                        ->grid(2)
                        ->columnSpanFull()
                        ->columns(2),
                    Repeater::make('params')
                        ->schema([
                            TextInput::make('param_name')
                                ->label('Parameter Name')
                                ->required(),
                            TextInput::make('param_value')
                                ->label('Parameter Value')
                                ->required(),
                        ])
                        ->label('Additional Parameters')
                        ->grid(2)
                        ->columns(2)
                        ->columnSpanFull()
                        ->rules(['distinct:param_name']),
                    Toggle::make('is_active')
                        ->required()
                        ->inline(false),
                ])->columns(2),
            ])->columns(1);
    }
}
