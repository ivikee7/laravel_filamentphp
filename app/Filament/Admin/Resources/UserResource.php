<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\Pages\IDCard;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'User and Attendance';

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
                                        Forms\Components\TextInput::make('name')->required(),
                                        Forms\Components\TextInput::make('official_email')->email(),
                                    ])
                                    ->columnSpan(1),
                            ]),
                    ]),
                Section::make('Parents info')
                    ->schema([
                        Forms\Components\TextInput::make('father_name')->required(),
                        Forms\Components\TextInput::make('mother_name')->required(),
                    ])->columns(2),
                Section::make('Contact info')
                    ->schema([
                        Forms\Components\TextInput::make('primary_contact_number')
                            ->numeric()
                            ->rules(['digits:10'])
                            ->minLength(10)
                            ->maxLength(10)
                            ->required(),
                        Forms\Components\TextInput::make('secondary_contact_number')
                            ->numeric()
                            ->rules(['digits:10'])
                            ->minLength(10)
                            ->maxLength(10)
                            ->required(),
                        Forms\Components\TextInput::make('email')->email()->required(),
                    ])->columns(2),
                Section::make('Address')
                    ->schema([
                        Forms\Components\TextInput::make('address')->required(),
                        Forms\Components\TextInput::make('city')->required(),
                        Forms\Components\TextInput::make('state')->required(),
                        Forms\Components\TextInput::make('pin_code')
                            ->numeric()
                            ->rules(['digits:6'])
                            ->minLength(6)
                            ->maxLength(6)
                            ->required(),
                    ])->columns(2),
                Section::make('Financial Info')
                    ->schema([
                        Forms\Components\Group::make()
                            ->relationship('financialDetails')
                            ->schema([
                                // Basic salary and allowances
                                Forms\Components\TextInput::make('basic_salary')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('house_allowance')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('transport_allowance')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('other_allowances')
                                    ->numeric()
                                    ->required(),

                                // Deductions
                                Forms\Components\TextInput::make('tax_deduction')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('loan_deduction')
                                    ->numeric()
                                    ->required(),

                                // Payment info
                                Forms\Components\TextInput::make('bank_name')
                                    ->required(),
                                Forms\Components\TextInput::make('account_number')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('ifsc_code')
                                    ->required(),

                                // Provident fund
                                Forms\Components\TextInput::make('provident_fund_contribution')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('pf_account_number')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('esi_number')
                                    ->numeric()
                                    ->required(),

                                // Extra
                                Forms\Components\TextInput::make('notes')
                                    ->required(),
                            ])->columns(3)
                    ]),
                Section::make('Authentication info')
                    ->schema([
                        Forms\Components\Select::make('roles')
                            ->relationship('roles', 'name', function ($query) {
                                $query->whereNotIn('name', ['Student']); // Exclude 'Student'

                                // If the authenticated user is NOT a "Super Admin", also exclude "Super Admin"
                                if (!Auth::user()->hasRole('Super Admin')) {
                                    $query->where('name', '!=', 'Super Admin');
                                }

                                return $query;
                            }),
                        Forms\Components\Toggle::make('is_active')->inline(false)->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->size(50)
                    ->label('Image')
                    ->getStateUsing(function ($record) {
                        return $record->avatar
                            ? 'storage/' . $record->avatar
                            : 'https://ui-avatars.com/api/?name=' . urlencode($record->name);
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('father_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mother_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
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
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ->modifyQueryUsing(function (Builder $query) {
                if (!Auth::user()->hasRole('Super Admin')) {
                    $query->withoutRole('Super Admin');
                }
                $query->withoutRole('Student');
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'monthly-attendance' => Pages\MonthlyAttendance::route('/monthly-attendance'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'id-card' => Pages\IDCard::route('/{record}/id-card'),
            'transport' => Pages\Transport::route('/{record}/transport'),
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
        $authUser = Auth::user();
        // Super Admin can view all users, including other Super Admins
        if ($authUser->hasRole('Super Admin')) {
            return true;
        }
        // Other users cannot view Super Admin users
        return !$record->hasRole('Super Admin') && !$record->hasRole('Student');
    }

    public static function canEdit(Model $record): bool
    {
        $authUser = Auth::user();
        // Super Admin can edit any user, including other Super Admins
        if ($authUser->hasRole('Super Admin')) {
            return true;
        }
        // Other users cannot edit Super Admin or Student users
        return !$record->hasRole('Super Admin') && !$record->hasRole('Student');
    }

    public static function canDelete(Model $record): bool
    {
        $authUser = Auth::user();
        // Super Admin can delete any user, including other Super Admins
        if ($authUser->hasRole('Super Admin')) {
            return true;
        }
        // Other users cannot delete Super Admin or Student users
        return !$record->hasRole('Super Admin') && !$record->hasRole('Student');
    }

    public static function getNavigationBadge(): ?string
    {
        return User::where('is_active', 1)
            ->whereHas('roles', function ($query) {
                if (!Auth::user()->hasRole('Super Admin')) {
                    $query->whereNot('name', 'Super Admin');
                }
                $query->whereNot('name', 'Student');
            })
            ->count();
    }
}
