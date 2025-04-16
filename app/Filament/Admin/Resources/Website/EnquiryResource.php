<?php

namespace App\Filament\Admin\Resources\Website;

use App\Filament\Admin\Resources\Website\EnquiryResource\Pages;
use App\Filament\Admin\Resources\Website\EnquiryResource\RelationManagers;
use App\Models\WebsiteEnquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Livewire\wrap;

class EnquiryResource extends Resource
{
    protected static ?string $model = WebsiteEnquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Website Enquiry';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->maxLength(50)
                    ->default(null)
                    ->disabled()
                    ->required(),
                Forms\Components\TextInput::make('contact_number')
                    ->numeric()
                    ->rules(['digits:10'])
                    ->default(null)
                    ->disabled()
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(50)
                    ->default(null)
                    ->disabled()
                    ->required(),
                Forms\Components\TextInput::make('message')
                    ->maxLength(255)
                    ->default(null)
                    ->disabled()
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->label('Follow-up notes')
                    ->maxLength(150)
                    ->default(null)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('message')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Follow-up notes')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
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
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListEnquiries::route('/'),
            'create' => Pages\CreateEnquiry::route('/create'),
            'view' => Pages\ViewEnquiry::route('/{record}'),
            'edit' => Pages\EditEnquiry::route('/{record}/edit'),
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
        return WebsiteEnquiry::count();
    }
}
