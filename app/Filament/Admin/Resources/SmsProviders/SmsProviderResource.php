<?php

namespace App\Filament\Admin\Resources\SmsProviders;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Admin\Resources\SmsProviders\Pages\ListSmsProviders;
use App\Filament\Admin\Resources\SmsProviders\Pages\CreateSmsProvider;
use App\Filament\Admin\Resources\SmsProviders\Pages\ViewSmsProvider;
use App\Filament\Admin\Resources\SmsProviders\Pages\EditSmsProvider;
use App\Filament\Admin\Resources\SmsProviders\Pages\SendSms;
use App\Filament\Admin\Resources\SmsProviderResource\Pages;
use App\Filament\Admin\Resources\SmsProviderResource\RelationManagers;
use App\Models\SmsProvider;
use Closure;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SmsProviderResource extends Resource
{
    protected static ?string $model = SmsProvider::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'SMS Services';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('base_url')
                            ->required()
                            ->maxLength(255),
                        Select::make('method')
                            ->options([
                                'get' => 'Get',
                                'post' => 'Post'
                            ])
                            ->required(),
                        TextInput::make('to_key')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('text_key')
                            ->required()
                            ->maxLength(255),
                        Repeater::make('params')
                            ->schema([
                                TextInput::make('param_name')
                                    ->label('Parameter Name')
                                    ->required()
                                    ->rules(['distinct']),

                                TextInput::make('param_value')
                                    ->label('Parameter Value')
                                    ->required(),
                            ])
                            ->label('Parameter Mappings')
                            ->grid(2)
                            ->columnSpanFull()
                            ->columns(2),
                        Repeater::make('headers')
                            ->schema([
                                TextInput::make('header_name')
                                    ->label('Header Name')
                                    ->required()
                                    ->rules(['distinct']),
                                TextInput::make('header_value')
                                    ->label('Header Value')
                                    ->required(),
                            ])
                            ->label('Header Mappings')
                            ->grid(2)
                            ->columnSpanFull()
                            ->columns(2),
                        Repeater::make('responses')
                            ->schema([
                                TextInput::make('response_name')
                                    ->label('Response Name')
                                    ->required()
                                    ->rules(['distinct']),
                                TextInput::make('response_value')
                                    ->label('Response Value')
                                    ->required(),
                                Toggle::make('is_display')
                                    ->label('Display in Notification')
                                    ->default(false)
                                    ->inline(false),
                            ])
                            ->label('Response Mappings')
                            ->grid(2)
                            ->columnSpanFull()
                            ->columns(2),
                        Toggle::make('is_active')
                            ->required()
                            ->inline(false),

                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('base_url')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('method'),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('creator.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updater.name')
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
            ->defaultSort('id', 'desc')
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
            'index' => ListSmsProviders::route('/'),
            'create' => CreateSmsProvider::route('/create'),
            'view' => ViewSmsProvider::route('/{record}'),
            'edit' => EditSmsProvider::route('/{record}/edit'),
            'sendSms' => SendSms::route('/{record}/send-sms'), // ✅ Add this line
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
