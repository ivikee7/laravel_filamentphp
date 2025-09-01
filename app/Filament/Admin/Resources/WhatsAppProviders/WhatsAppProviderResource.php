<?php

namespace App\Filament\Admin\Resources\WhatsAppProviders;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Admin\Resources\WhatsAppProviders\Pages\ListWhatsAppProviders;
use App\Filament\Admin\Resources\WhatsAppProviders\Pages\CreateWhatsAppProvider;
use App\Filament\Admin\Resources\WhatsAppProviders\Pages\ViewWhatsAppProvider;
use App\Filament\Admin\Resources\WhatsAppProviders\Pages\EditWhatsAppProvider;
use App\Filament\Admin\Resources\WhatsAppProviderResource\Pages;
use App\Filament\Admin\Resources\WhatsAppProviderResource\RelationManagers;
use App\Models\WhatsAppProvider;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WhatsAppProviderResource extends Resource
{
    protected static ?string $model = WhatsAppProvider::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'WhatsApp';

    protected static ?string $navigationLabel = 'Providers';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('🔧 Basic Config')
                    ->schema([
                        Group::make()
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('base_url')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('send_message_endpoint')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('api_token')
                                    ->maxLength(255)
                                    ->default(null),
                            ])->columns(2),

                        Group::make()
                            ->schema([
                                TextInput::make('verify_token')
                                    ->maxLength(255)
                                    ->default(null),
                                TextInput::make('encryption_key')
                                    ->maxLength(255)
                                    ->default(null),
                            ])->columns(2),
                        Group::make()
                            ->schema([
                                Repeater::make('headers')
                                    ->required()
                                    ->label('Headers Mappings')
                                    ->schema([
                                        TextInput::make('header_name')
                                            ->label('Header Name')
                                            ->required()
                                            ->rules(['distinct'])
                                            ->helperText('Provide a unique name for the header.'),
                                        TextInput::make('header_value')
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
                        TextInput::make('phone_number')
                            ->maxLength(255)
                            ->default(null),
                        TextInput::make('phone_number_id')
                            ->maxLength(255)
                            ->default(null),
                        TextInput::make('business_account_id')
                            ->maxLength(255)
                            ->default(null),
                        DateTimePicker::make('token_expires_at')
                            ->default(null),
                    ])->columns(2),
                Section::make('🔁 Webhook Management')
                    ->schema([
                        TextInput::make('webhook_url')
                            ->maxLength(255)
                            ->default(null),
                        DateTimePicker::make('webhook_received_at')
                            ->default(null),
                        TextInput::make('webhook_status')
                            ->maxLength(100)
                            ->default(null),
                        Textarea::make('last_error_message')
                            ->maxLength(255)
                            ->default(null),
                        TextInput::make('failed_webhook_count')
                            ->numeric()
                            ->default(null),
                        DateTimePicker::make('last_successful_response')
                            ->default(null),
                    ])->columns(2),
                Section::make('🔐 Meta App Info')
                    ->schema([
                        TextInput::make('meta_app_id')
                            ->maxLength(255)
                            ->default(null),
                        TextInput::make('meta_app_secret')
                            ->maxLength(255)
                            ->default(null),
                    ])->columns(2),
                Section::make('⚙️ Operational Controls')
                    ->schema([
                        Toggle::make('is_active')->inline(false)->required(),
                        Toggle::make('is_default')->inline(false)->required(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('phone_number')
                    ->searchable(),
                TextColumn::make('created_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
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
            'index' => ListWhatsAppProviders::route('/'),
            'create' => CreateWhatsAppProvider::route('/create'),
            'view' => ViewWhatsAppProvider::route('/{record}'),
            'edit' => EditWhatsAppProvider::route('/{record}/edit'),
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
