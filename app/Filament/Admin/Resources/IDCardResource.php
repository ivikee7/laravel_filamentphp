<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\IDCardResource\Pages;
use App\Filament\Admin\Resources\IDCardResource\RelationManagers;
use App\Models\IDCard;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class IDCardResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'IDCard';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->size(50)
                    ->label('Image'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('father_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mother_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->searchable(),
                Tables\Columns\ViewColumn::make('qr_code')
                    ->label('QR Code')
                    ->view('components.qr-code-column')
                    ->extraAttributes(['style' => 'text-align: center;'])
                    ->state(function ($record) {
                        return IDCardResource::getUrl('view', ['record' => $record->id]);
                    }),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $query->withoutRole('Super Admin');
                // $query->withoutRole('Owner');
                $query->withoutRole('Admin');
                $query->withoutRole('Teacher');
            })
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
            'index' => Pages\ListIDCards::route('/'),
            'create' => Pages\CreateIDCard::route('/create'),
            'view' => Pages\ViewIDCard::route('/{record}'),
            'edit' => Pages\EditIDCard::route('/{record}/edit'),
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
