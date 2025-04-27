<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EnquiryResource\Pages;
use App\Filament\Admin\Resources\EnquiryResource\RelationManagers;
use App\Models\Enquiry;
use App\Models\Gender;
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


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Student info')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\Select::make('gender_id')
                            ->options(Gender::pluck('name', 'id'))
                            ->required(),
                        Forms\Components\DatePicker::make('date_of_birth'),
                    ])->columns(3),
                Section::make('Preveius School info')
                    ->schema([
                        Forms\Components\TextInput::make('previous_school')
                            ->maxLength(50)
                            ->default(null),
                        Forms\Components\Select::make('previous_class_id')
                            ->label('Previous Class')
                            ->relationship('class', 'name')
                            ->default(null),
                    ])->columns(3),
                Section::make('Admission info')
                    ->schema([
                        Forms\Components\Select::make('class_id')
                        ->label('Enquiry Class')
                            ->relationship('class', 'name')
                            ->default(null),
                        Forms\Components\Textarea::make('notes')
                            ->required()
                            ->columnSpan(2)
                            ->maxLength(255)
                            ->rows(5)
                            ->cols(1)
                    ])->columns(3),
                Section::make('Parents info')
                    ->schema([
                        Forms\Components\TextInput::make('father_name')
                            ->maxLength(50)
                            ->default(null),
                        Forms\Components\TextInput::make('mother_name')
                            ->maxLength(50)
                            ->default(null),
                        Forms\Components\TextInput::make('email')->email(),
                        Forms\Components\TextInput::make('primary_contact_number')->required()
                            ->numeric()
                            ->rules(['digits:10'])
                            ->minLength(10)
                            ->maxLength(10),
                        Forms\Components\TextInput::make('secondary_contact_number')
                            ->numeric()
                            ->rules(['digits:10'])
                            ->minLength(10)
                            ->maxLength(10),
                    ])->columns(3),
                Section::make('Mother info')
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
                            ->rules(['digits:6'])
                            ->minLength(6)
                            ->maxLength(6),
                    ])->columns(3),
                Section::make('Other info')
                    ->schema([
                        Forms\Components\Select::make('source')
                            ->options([
                                'OTHER' => 'OTHER',
                                'HOADING' => 'HOADING',
                                'RELEVENT' => 'RELEVENT',
                                'SOCIAL MEDIA' => 'SOCIAL MEDIA',
                                'WEBSITE' => 'WEBSITE',
                            ])
                            ->default(null)
                            ->required(),
                    ])->columns(3),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('class.name')->wrap()
                    ->label('Enquiry Class')
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('father_name')->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mother_name')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('primary_contact_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('secondary_contact_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('address')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pin_code')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('previous_school')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('notes')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('source')->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('class.name')->wrap()
                    ->label('Previous Class')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')->wrap()
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
                Tables\Actions\Action::make('register')
                    ->label('Registration')
                    ->url(fn(Enquiry $record) => RegistrationResource::getUrl('create', [
                        'enquiry_id' => $record->id, // Pass enquiry ID to Registration form
                    ])),
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
        return Enquiry::count();
    }
}
