<?php

namespace App\Filament\Resources\School;

use App\Filament\Resources\School\EnquiryResource\Pages;
use App\Filament\Resources\School\EnquiryResource\RelationManagers;
use App\Models\School\Enquiry;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnquiryResource extends Resource
{
    protected static ?string $model = Enquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Student Management System';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Enquiry info')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\Select::make('class_id')
                            ->relationship('class', 'name')
                            ->required(),

                        Forms\Components\TextInput::make('father_name')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('mother_name')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('father_contact_name')
                            ->required()
                            ->length(10)
                            ->numeric(),
                        Forms\Components\TextInput::make('mother_contact_name')
                            ->required()
                            ->length(10)
                            ->numeric(),

                        Forms\Components\Textarea::make('message')
                            ->maxLength(100)
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('class.name')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('father_name')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('mother_name')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('father_contact_name')
                    ->label('Fathers number')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('mother_contact_name')
                    ->label('Mothers number')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('message')
                    ->wrap(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updater.name')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->wrap()
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->wrap()
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->wrap()
                    ->dateTime()
                    ->sortable()
                    ->searchable()
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
}
