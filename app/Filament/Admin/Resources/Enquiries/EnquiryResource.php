<?php

namespace App\Filament\Admin\Resources\Enquiries;

use App\Filament\Admin\Resources\Registrations\RegistrationResource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Admin\Resources\Enquiries\Pages\ListEnquiries;
use App\Filament\Admin\Resources\Enquiries\Pages\CreateEnquiry;
use App\Filament\Admin\Resources\Enquiries\Pages\ViewEnquiry;
use App\Filament\Admin\Resources\Enquiries\Pages\EditEnquiry;
use App\Filament\Admin\Resources\EnquiryResource\Pages;
use App\Filament\Admin\Resources\EnquiryResource\RelationManagers;
use App\Models\Enquiry;
use App\Models\Gender;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnquiryResource extends Resource
{
    protected static ?string $model = Enquiry::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Student info')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(50),
                        Select::make('gender_id')
                            ->options(Gender::pluck('name', 'id'))
                            ->required(),
                        DatePicker::make('date_of_birth'),
                    ])->columns(3),
                Section::make('Preveius School info')
                    ->schema([
                        TextInput::make('previous_school')
                            ->maxLength(50)
                            ->default(null),
                        Select::make('previous_class_id')
                            ->label('Previous Class')
                            ->relationship('class', 'name')
                            ->default(null),
                    ])->columns(3),
                Section::make('Admission info')
                    ->schema([
                        Select::make('class_id')
                            ->label('Enquiry Class')
                            ->relationship('class', 'name')
                            ->default(null),
                        Textarea::make('notes')
                            ->required()
                            ->columnSpan(2)
                            ->maxLength(100)
                            ->rows(5)
                            ->cols(1)
                    ])->columns(3),
                Section::make('Parents info')
                    ->schema([
                        TextInput::make('father_name')
                            ->maxLength(50)
                            ->default(null),
                        TextInput::make('mother_name')
                            ->maxLength(50)
                            ->default(null),
                        TextInput::make('email')->email(),
                        TextInput::make('primary_contact_number')->required()
                            ->numeric()
                            ->rules(['digits:10'])
                            ->minLength(10)
                            ->maxLength(10),
                        TextInput::make('secondary_contact_number')
                            ->numeric()
                            ->rules(['digits:10'])
                            ->minLength(10)
                            ->maxLength(10),
                    ])->columns(3),
                Section::make('Mother info')
                    ->schema([
                        TextInput::make('address')
                            ->maxLength(255)
                            ->default(null),
                        TextInput::make('city')
                            ->maxLength(25)
                            ->default(null),
                        TextInput::make('state')
                            ->maxLength(25)
                            ->default(null),
                        TextInput::make('pin_code')
                            ->numeric()
                            ->rules(['digits:6'])
                            ->minLength(6)
                            ->maxLength(6),
                    ])->columns(3),
                Section::make('Other info')
                    ->schema([
                        Select::make('source')
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

            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('class.name')->wrap()
                    ->label('Class')
                    ->sortable(),
                TextColumn::make('gender')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('father_name')->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mother_name')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('primary_contact_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('secondary_contact_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('address')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('city')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('state')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pin_code')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('previous_school')->wrap()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('notes')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('source')->wrap()
                    ->searchable(),
                TextColumn::make('previousClass.name')->wrap()
                    ->label('Previous Class')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')->wrap()
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
                Action::make('register')
                    ->label('Registration')
                    ->url(fn(Enquiry $record) => RegistrationResource::getUrl('create', [
                        'enquiry_id' => $record->id, // Pass enquiry ID to Registration form
                    ])),
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
            'index' => ListEnquiries::route('/'),
            'create' => CreateEnquiry::route('/create'),
            'view' => ViewEnquiry::route('/{record}'),
            'edit' => EditEnquiry::route('/{record}/edit'),
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
