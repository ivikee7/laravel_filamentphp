<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MessageTemplateResource\Pages;
use App\Filament\Admin\Resources\MessageTemplateResource\RelationManagers;
use App\Models\MessageTemplate;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MessageTemplateResource extends Resource
{
    protected static ?string $model = MessageTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'SMS Services';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\Select::make('sms_provider_id')
                        ->relationship('smsProvider', 'name', function ($query) {
                            $query->where('is_active', true);
                            return $query;
                        }),
                    Forms\Components\TextInput::make('name')
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('content')
                        ->label('Template')
                        ->hint('Use placeholders like {{name}}, {{date}}')
                        ->reactive()
                        ->helperText(fn($state) => strlen($state) . ' / 160 characters') // Live count
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\Repeater::make('variables')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Variable Name')
                                ->required(),

                            Forms\Components\Select::make('column')
                                ->label('Mapped User Field')
                                ->options(array_combine(
                                    (new \App\Models\User())->getFillable(),
                                    (new \App\Models\User())->getFillable()
                                ))
                                ->searchable()
                                ->required(),
                        ])
                        ->label('Expected Variables')
                        ->grid(2)
                        ->columnSpanFull()
                        ->columns(2),
                    Forms\Components\Repeater::make('params')
                        ->schema([
                            Forms\Components\TextInput::make('param_name')
                                ->label('Parameter Name')
                                ->required(),
                            Forms\Components\TextInput::make('param_value')
                                ->label('Parameter Value')
                                ->required(),
                        ])
                        ->label('Additional Parameters')
                        ->grid(2)
                        ->columns(2)
                        ->columnSpanFull()
                        ->rules(['distinct:param_name']),
                    Forms\Components\Toggle::make('is_active')
                        ->required()
                        ->inline(false),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('content')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updatedBy.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMessageTemplates::route('/'),
            'create' => Pages\CreateMessageTemplate::route('/create'),
            'view' => Pages\ViewMessageTemplate::route('/{record}'),
            'edit' => Pages\EditMessageTemplate::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
