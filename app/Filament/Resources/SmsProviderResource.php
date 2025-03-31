<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SmsProviderResource\Pages;
use App\Filament\Resources\SmsProviderResource\RelationManagers;
use App\Models\SmsProvider;
use Filament\Forms;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('api_url')->url()->required(),
                Forms\Components\TextInput::make('api_key')->password()->required(),
                Forms\Components\TextInput::make('sender_id')->required(),
                Forms\Components\Toggle::make('is_active')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('api_url')->label('API URL')->limit(30),
                Tables\Columns\TextColumn::make('sender_id')->label('Sender ID'),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
            ])
            ->filters([])
            ->actions([])
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
