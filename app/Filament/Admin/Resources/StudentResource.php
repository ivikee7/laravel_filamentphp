<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StudentResource\Pages;
use App\Filament\Admin\Resources\StudentResource\RelationManagers;
use App\Models\AcademicYear;
use App\Models\BloodGroup;
use App\Models\Classes;
use App\Models\Gender;
use App\Models\MessageTemplate;
use App\Models\Quota;
use App\Models\Registration;
use App\Models\SmsProvider;
use App\Models\Student;
use App\Models\User;
use App\Services\SMSService;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'User';
    protected static ?string $modelLabel = 'Student';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Section::make('User info')
                    ->schema([
                        Forms\Components\Grid::make(2) // Create a 2-column layout
                            ->schema([
                                // Left Column: Centered Large Avatar
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\FileUpload::make('avatar')
                                            ->image()
                                            ->avatar()
                                            ->imageEditor()
                                            ->hiddenLabel()
                                            ->imagePreviewHeight(250)
                                            // disk
                                            ->disk('public')
                                            ->directory('media/avatar')
                                            ->visibility('public')
                                    ])
                                    ->columnSpan(1)
                                    ->extraAttributes([
                                        'style' => 'display: flex; align-items: center; justify-content: center; height: 100%;',
                                    ]),

                                // Right Column: Other Input Fields
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->name),
                                        Forms\Components\TextInput::make('official_email')->email()
                                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->official_email),
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Select::make('gender_id')
                                                    ->options(Gender::pluck('name', 'id'))
                                                    ->required(),
                                                Forms\Components\Select::make('blood_group_id')
                                                    ->options(BloodGroup::pluck('name', 'id'))
                                                    ->required(),
                                            ])->columns(2),
                                    ])
                                    ->columnSpan(1),
                            ]),
                    ]),

                Section::make('Admission Info')
                    ->schema([
                        Forms\Components\Group::make()
                            ->relationship('currentStudent')
                            ->schema([
                                Forms\Components\DatePicker::make('admission_date')
                                    ->label('Admission Date'),
                                Forms\Components\Select::make('current_status')
                                    ->options([
                                        'active' => 'Active',
                                        'graduated' => 'Graduated',
                                        'left' => 'Left',
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('quota_id')
                                    ->options(Quota::pluck('name', 'id'))
                                    ->required(),
                            ])->columns(3),

                        Forms\Components\Group::make()
                            ->relationship('currentStudent')
                            ->schema([
                                Forms\Components\Group::make()
                                    ->relationship('currentClassAssignment')
                                    ->schema([
                                        Forms\Components\Select::make('academic_year_id')
                                            ->label('Academic Year')
                                            ->options(AcademicYear::pluck('name', 'id'))
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(fn($set) => [
                                                $set('class_id', null),
                                                $set('section_id', null),
                                            ]),

                                        Forms\Components\Select::make('class_id')
                                            ->label('Class')
                                            ->options(
                                                fn($get) =>
                                                $get('academic_year_id')
                                                    ? Classes::where('academic_year_id', $get('academic_year_id'))->pluck('name', 'id')
                                                    : []
                                            )
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(fn($set) => $set('section_id', null))
                                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->class_id),

                                        Forms\Components\Select::make('section_id')
                                            ->label('Section')
                                            ->required()
                                            ->options(function ($get) {
                                                $classId = $get('class_id');
                                                return $classId
                                                    ? \App\Models\Section::where('class_id', $classId)->pluck('name', 'id')->toArray()
                                                    : [];
                                            })
                                            ->default(fn($get) => \App\Models\Registration::find(request()->query('registration_id'))?->section_id),

                                    ])->columns(3),
                            ]),
                    ]),
                Section::make('Parents info')
                    ->schema([
                        Forms\Components\TextInput::make('father_name')->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->father_name),
                        Forms\Components\TextInput::make('mother_name')->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->mother_name),
                    ])->columns(2),
                Section::make('Contact info')
                    ->schema([
                        Forms\Components\TextInput::make('primary_contact_number')
                            ->numeric()
                            ->rules(['digits:10'])
                            ->minLength(10)
                            ->maxLength(10)
                            ->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->primary_contact_number),
                        Forms\Components\TextInput::make('secondary_contact_number')
                            ->numeric()
                            ->rules(['digits:10'])
                            ->minLength(10)
                            ->maxLength(10)
                            ->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->secondary_contact_number),
                        Forms\Components\TextInput::make('email')->email()->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->email),
                    ])->columns(2),
                Section::make('Address')
                    ->schema([
                        Forms\Components\TextInput::make('address')->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->address),
                        Forms\Components\TextInput::make('city')->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->city),
                        Forms\Components\TextInput::make('state')->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->state),
                        Forms\Components\TextInput::make('pin_code')
                            ->numeric()
                            ->rules(['digits:6'])
                            ->minLength(6)
                            ->maxLength(6)
                            ->required()
                            ->default(fn($get) => Registration::find(request()->query('registration_id'))?->pin_code),
                    ])->columns(2),

                // start only for deleteing registration after admission
                Forms\Components\TextInput::make('registration_id')
                    ->hidden()
                    ->default(fn() => request()->query('registration_id')),
                // end only for deleteing registration after admission
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->size(50)
                    ->label('Image')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('name')
                    ->wrap()
                    ->searchable()
                    ->label('Name'),
                Tables\Columns\TextColumn::make('father_name')
                    ->wrap()
                    ->searchable()
                    ->label('Father Name')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('mother_name')
                    ->wrap()
                    ->searchable()
                    ->label('Motner Name')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('student.quota.name')
                    ->sortable()->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bloodGroup.name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('gender.name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Email'),
                Tables\Columns\TextColumn::make('official_email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('is_active')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state ? 'Active' : 'Suspended')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Created At')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Updated At')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Deleted At')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->modifyQueryUsing(function (Builder $query) {
                $query->Role('Student');
            })
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('is_active')
                    ->options([
                        true => 'Active',
                        false => 'Suspended',
                    ])
                    ->label('Status')
                    ->default(true)
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
                    BulkAction::make('send_bulk_sms')
                        ->label('Send Bulk SMS')
                        ->form([
                            Forms\Components\Select::make('provider_id')
                                ->label('SMS Provider')
                                ->options(SMSProvider::query()->where('is_active', true)->pluck('name', 'id'))
                                ->searchable()
                                ->required(),

                            Forms\Components\Select::make('template_id')
                                ->label('Message Template')
                                ->options(MessageTemplate::all()->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $provider = SmsProvider::find($data['provider_id']);

                            if (!$provider || !$provider->is_active) {
                                Notification::make()
                                    ->title('SMS Provider Error')
                                    ->body('SMS provider not found or inactive.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $template = MessageTemplate::find($data['template_id']);
                            $smsService = new SMSService($provider->toArray()); // assuming SMSService accepts provider

                            foreach ($records as $student) {
                                $message = str_replace(
                                    ['{{name}}', '{{time}}'],
                                    [
                                        $student->name,
                                        optional($student->class)->name ?? '',
                                        $student->roll_no ?? '',
                                    ],
                                    $template->content
                                );

                                $smsService->sendSms($student->primary_contact_number, $message, $template);
                            }

                            Notification::make()
                                ->title('SMS Sent')
                                ->body('Bulk SMS sent successfully!')
                                ->success()
                                ->send();
                        }),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function canView(Model $record): bool
    {
        return $record->hasRole('Student');
    }

    public static function canEdit(Model $record): bool
    {
        return $record->hasRole('Student');
    }

    public static function canDelete(Model $record): bool
    {
        return $record->hasRole('Student');
    }

    public static function getNavigationBadge(): ?string
    {
        return User::where('is_active', 1)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Student');
            })
            ->count();
    }
}
