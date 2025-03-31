<?php

namespace App\Filament\Resources\School;

use App\Filament\Resources\School\RegistrationResource\Pages;
use App\Filament\Resources\School\RegistrationResource\RelationManagers;
use App\Models\Registration;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Student Management System';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Student info')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\DatePicker::make('date_of_birth'),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'M' => 'Male',
                                'F' => 'Female',
                                'O' => 'Other',
                            ])
                            ->required(),
                        Forms\Components\Select::make('class_id')
                            ->relationship('class', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('last_attended_school')
                            ->maxLength(255)
                            ->default(null),
                        Forms\Components\Select::make('last_attended_class_id')
                            ->relationship('lastAttendedClass', 'name')
                            ->default(null),
                    ])->columns(3),
                Section::make('Fathers info')
                    ->schema([
                        Forms\Components\TextInput::make('father_name')
                            ->maxLength(50)
                            ->default(null),
                        Forms\Components\TextInput::make('father_qualification')
                            ->maxLength(50)
                            ->default(null),
                        Forms\Components\TextInput::make('father_occupation')
                            ->maxLength(50)
                            ->default(null),
                        Forms\Components\TextInput::make('father_contact_number')
                            ->length(10)
                            ->default(null),
                    ])->columns(3),
                Section::make('Mothers info')
                    ->schema([
                        Forms\Components\TextInput::make('mother_name')
                            ->maxLength(50)
                            ->default(null),
                        Forms\Components\TextInput::make('mother_qualification')
                            ->maxLength(50)
                            ->default(null),
                        Forms\Components\TextInput::make('mother_occupation')
                            ->maxLength(50)
                            ->default(null),
                        Forms\Components\TextInput::make('mother_contact_number')
                            ->length(10)
                            ->default(null),
                    ])->columns(3),
                Section::make('Address')
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->maxLength(255)
                            ->default(null),
                        Forms\Components\TextInput::make('city')
                            ->maxLength(25)
                            ->default(null),
                        Forms\Components\TextInput::make('state')
                            ->maxLength(25)
                            ->default(null),
                        Forms\Components\TextInput::make('pin_code')
                            ->numeric()
                            ->default(null),
                    ])->columns(3),
                Section::make('Payment Info')
                    ->schema([
                        Forms\Components\Select::make('payment_mode')
                            ->options([
                                'Online' => 'Online',
                                'Cash' => 'Cash',
                            ])
                            ->required(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('father_name')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('father_qualification')
                    ->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('father_occupation')
                    ->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('father_contact_number')
                    ->label('Mothers Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mother_name')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mother_qualification')
                    ->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('mother_occupation')
                    ->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('mother_contact_number')
                    ->label('Fathers Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city')
                    ->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state')
                    ->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pin_code')
                    ->wrap()
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('class.name')
                    ->wrap()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_attended_school')
                    ->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('lastAttendedClass.name')
                    ->wrap()
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment_mode')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->wrap()
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updater.name')
                    ->wrap()
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->wrap()
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
            'index' => Pages\ListRegistrations::route('/'),
            'create' => Pages\CreateRegistration::route('/create'),
            'view' => Pages\ViewRegistration::route('/{record}'),
            'edit' => Pages\EditRegistration::route('/{record}/edit'),
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
