<?php

namespace App\Filament\Admin\Resources\SentMessages;

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
use App\Filament\Admin\Resources\SentMessages\Pages\ListSentMessages;
use App\Filament\Admin\Resources\SentMessages\Pages\CreateSentMessage;
use App\Filament\Admin\Resources\SentMessages\Pages\ViewSentMessage;
use App\Filament\Admin\Resources\SentMessages\Pages\EditSentMessage;
use App\Filament\Admin\Resources\SentMessageResource\Pages;
use App\Filament\Admin\Resources\SentMessageResource\RelationManagers;
use App\Models\SentMessage;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SentMessageResource extends Resource
{
    protected static ?string $model = SentMessage::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'SMS Services';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('response')
                    ->columnSpanFull(),
                TextInput::make('provider_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('message')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('response')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('provider.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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
            'index' => ListSentMessages::route('/'),
            'create' => CreateSentMessage::route('/create'),
            'view' => ViewSentMessage::route('/{record}'),
            'edit' => EditSentMessage::route('/{record}/edit'),
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
