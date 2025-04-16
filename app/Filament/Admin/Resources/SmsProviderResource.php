<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SmsProviderResource\Pages;
use App\Filament\Admin\Resources\SmsProviderResource\RelationManagers;
use App\Models\SmsProvider;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SmsProviderResource extends Resource
{
    protected static ?string $model = SmsProvider::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'SMS Services';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('base_url')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('method')
                            ->options([
                                'get' => 'Get',
                                'post' => 'Post'
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('to_key')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('text_key')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Repeater::make('params')
                            ->schema([
                                Forms\Components\TextInput::make('param_name')
                                    ->label('Parameter Name')
                                    ->required()
                                    ->rules(['distinct']),

                                Forms\Components\TextInput::make('param_value')
                                    ->label('Parameter Value')
                                    ->required(),
                            ])
                            ->label('Parameter Mappings')
                            ->grid(2)
                            ->columnSpanFull()
                            ->columns(2),
                        Forms\Components\Repeater::make('headers')
                            ->schema([
                                Forms\Components\TextInput::make('header_name')
                                    ->label('Header Name')
                                    ->required()
                                    ->rules(['distinct']),
                                Forms\Components\TextInput::make('header_value')
                                    ->label('Header Value')
                                    ->required(),
                            ])
                            ->label('Header Mappings')
                            ->grid(2)
                            ->columnSpanFull()
                            ->columns(2),
                        Forms\Components\Repeater::make('responses')
                            ->schema([
                                Forms\Components\TextInput::make('response_name')
                                    ->label('Response Name')
                                    ->required()
                                    ->rules(['distinct']),
                                Forms\Components\TextInput::make('response_value')
                                    ->label('Response Value')
                                    ->required(),
                                Forms\Components\Toggle::make('is_display')
                                    ->label('Display in Notification')
                                    ->default(false)
                                    ->inline(false),
                            ])
                            ->label('Response Mappings')
                            ->grid(2)
                            ->columnSpanFull()
                            ->columns(2),
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
                Tables\Columns\TextColumn::make('base_url')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('method'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updater.name')
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
            ->defaultSort('id', 'desc')
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
            'index' => Pages\ListSmsProviders::route('/'),
            'create' => Pages\CreateSmsProvider::route('/create'),
            'view' => Pages\ViewSmsProvider::route('/{record}'),
            'edit' => Pages\EditSmsProvider::route('/{record}/edit'),
            'sendSms' => Pages\SendSms::route('/{record}/send-sms'), // ✅ Add this line
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return SmsProvider::count();
    }
}
