<?php

namespace App\Filament\Admin\Resources\WhatsAppMessages;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Admin\Resources\WhatsAppMessages\Pages\ListWhatsAppMessages;
use App\Filament\Admin\Resources\WhatsAppMessages\Pages\CreateWhatsAppMessage;
use App\Filament\Admin\Resources\WhatsAppMessages\Pages\ViewWhatsAppMessage;
use App\Filament\Admin\Resources\WhatsAppMessages\Pages\EditWhatsAppMessage;
use App\Filament\Admin\Resources\WhatsAppMessageResource\Pages;
use App\Filament\Admin\Resources\WhatsAppMessageResource\RelationManagers;
use App\Models\WhatsAppMessage;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WhatsAppMessageResource extends Resource
{
    protected static ?string $model = WhatsAppMessage::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'WhatsApp';

    protected static ?string $navigationLabel = 'Messages';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('whatsapp_provider_id')
                    ->required()
                    ->numeric(),
                TextInput::make('to')
                    ->required()
                    ->maxLength(255),
                Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('pending'),
                Textarea::make('response')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('provider.name')
                    ->label('Provider')
                    ->sortable(),
                TextColumn::make('to')
                    ->searchable(),
                TextColumn::make('from_number')
                    ->searchable(),
                TextColumn::make('message')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('response')
                    ->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => ListWhatsAppMessages::route('/'),
            'create' => CreateWhatsAppMessage::route('/create'),
            'view' => ViewWhatsAppMessage::route('/{record}'),
            'edit' => EditWhatsAppMessage::route('/{record}/edit'),
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
