<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\WhatsAppProviderResource\Pages;
use App\Filament\Admin\Resources\WhatsAppProviderResource\RelationManagers;
use App\Models\WhatsAppProvider;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WhatsAppProviderResource extends Resource
{
    protected static ?string $model = WhatsAppProvider::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'WhatsApp';

    protected static ?string $navigationLabel = 'Providers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('🔧 Basic Config')
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('base_url')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('send_message_endpoint')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('api_token')
                                    ->maxLength(255)
                                    ->default(null),
                            ])->columns(2),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('verify_token')
                                    ->maxLength(255)
                                    ->default(null),
                                Forms\Components\TextInput::make('encryption_key')
                                    ->maxLength(255)
                                    ->default(null),
                            ])->columns(2),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Repeater::make('headers')
                                    ->required()
                                    ->label('Headers Mappings')
                                    ->schema([
                                        Forms\Components\TextInput::make('header_name')
                                            ->label('Header Name')
                                            ->required()
                                            ->rules(['distinct'])
                                            ->helperText('Provide a unique name for the header.'),
                                        Forms\Components\TextInput::make('header_value')
                                            ->label('Header Value')
                                            ->required()
                                            ->helperText('Provide the value for the header.'),
                                    ])
                                    ->columns(2) // Layout: two columns for each row of the repeater
                                    ->grid(2) // Number of items per row in the grid
                                    ->columnSpanFull()  // Spans the full width of the form
                                    ->createItemButtonLabel('Add Header'), // Optional: Set label for adding new items
                            ]),
                    ]),
                Section::make('📞 WhatsApp API Details')
                    ->schema([
                        Forms\Components\TextInput::make('phone_number')
                            ->maxLength(255)
                            ->default(null),
                        Forms\Components\TextInput::make('phone_number_id')
                            ->maxLength(255)
                            ->default(null),
                        Forms\Components\TextInput::make('business_account_id')
                            ->maxLength(255)
                            ->default(null),
                        Forms\Components\DateTimePicker::make('token_expires_at')
                            ->default(null),
                    ])->columns(2),
                Section::make('🔁 Webhook Management')
                    ->schema([
                        Forms\Components\TextInput::make('webhook_url')
                            ->maxLength(255)
                            ->default(null),
                        Forms\Components\DateTimePicker::make('webhook_received_at')
                            ->default(null),
                        Forms\Components\TextInput::make('webhook_status')
                            ->maxLength(100)
                            ->default(null),
                        Forms\Components\Textarea::make('last_error_message')
                            ->maxLength(255)
                            ->default(null),
                        Forms\Components\TextInput::make('failed_webhook_count')
                            ->numeric()
                            ->default(null),
                        Forms\Components\DateTimePicker::make('last_successful_response')
                            ->default(null),
                    ])->columns(2),
                Section::make('🔐 Meta App Info')
                    ->schema([
                        Forms\Components\TextInput::make('meta_app_id')
                            ->maxLength(255)
                            ->default(null),
                        Forms\Components\TextInput::make('meta_app_secret')
                            ->maxLength(255)
                            ->default(null),
                    ])->columns(2),
                Section::make('⚙️ Operational Controls')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')->inline(false)->required(),
                        Forms\Components\Toggle::make('is_default')->inline(false)->required(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_by')
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
            'index' => Pages\ListWhatsAppProviders::route('/'),
            'create' => Pages\CreateWhatsAppProvider::route('/create'),
            'view' => Pages\ViewWhatsAppProvider::route('/{record}'),
            'edit' => Pages\EditWhatsAppProvider::route('/{record}/edit'),
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
