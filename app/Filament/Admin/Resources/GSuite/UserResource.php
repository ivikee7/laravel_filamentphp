<?php

namespace App\Filament\Admin\Resources\GSuite;

use App\Filament\Admin\Resources\GSuite\UserResource\Pages;
use App\Filament\Admin\Resources\GSuite\UserResource\RelationManagers;
use App\Models\GSuiteUser;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Gate;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'G-Suite Users';
    protected static ?string $navigationGroup = 'G-Suite';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(50)
                    ->disabled(),
                Forms\Components\TextInput::make('gSuiteUser.email')
                    ->email()
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('gSuiteUser.password')
                    ->password()
                    ->revealable()
                    ->maxLength(255),
            ]);
    }

    public static function query()
    {
        // Simply return the query builder for the User model
        return User::query()->where('gSuiteUser.email', '!=', '')->hasRole('Student');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name')
                    ->wrap()
                    ->getStateUsing(function ($record) {
                        $parts = explode(' ', $record->name);
                        return count($parts) === 1 ? $parts[0] : implode(' ', array_slice($parts, 0, -1));
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name')
                    ->getStateUsing(function ($record) {
                        $parts = explode(' ', $record->name);

                        if (count($parts) > 1) {
                            $lastName = $parts[count($parts) - 1];
                            return $lastName ?: '_';
                        }

                        return '_';
                    }),

                Tables\Columns\TextColumn::make('gSuiteUser.email')
                    ->label('Email Address')
                    ->searchable(),

                Tables\Columns\TextColumn::make('gSuiteUser.password')
                    ->label('Password')
                    ->searchable(),

                Tables\Columns\TextColumn::make('orgUnitPath')
                    ->label('Org Unit Path')
                    ->getStateUsing(function ($record) {
                        $parts = ['/' . env('APP_NAME') . '/School'];

                        // Use the first available role name, or "User" if none
                        $roleName = $record->roles->pluck('name')->first() ?? 'User';
                        $role = ucfirst($roleName);
                        $parts[] = $role;

                        if ($role === 'Student' && $record->currentStudent?->currentClassAssignment) {
                            $assignment = $record->currentStudent->currentClassAssignment;

                            if ($assignment->class?->className?->name) {
                                $parts[] = $assignment->class->className->name;
                            }

                            if ($assignment->section?->name) {
                                $parts[] = $assignment->section->name;
                            }
                        }

                        return implode('/', $parts);
                    })
                    // ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('changePasswordAtNextSign-In')
                    ->label('Change Password at Next Sign-In')
                    ->default("FALSE")
                // ->searchable()
                ,
                Tables\Columns\TextColumn::make('is_active')
                    ->label('New Status [Upload Only]')
                    ->formatStateUsing(fn($state) => $state ? 'Active' : 'Suspended')
                    ->searchable(),

                Tables\Columns\TextColumn::make('gSuiteUser.created_by')
                    ->label('Created By')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('gSuiteUser.updated_by')
                    ->label('Updated By')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('gSuiteUser.deleted_by')
                    ->label('Deleted By')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('gSuiteUser.created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('gSuiteUser.updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('gSuiteUser.deleted_at')
                    ->label('Deleted At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->paginated([5, 25, 50, 100, 500])
            ->defaultPaginationPageOption(5)
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('is_active')
                    ->options([
                        true => 'Active',
                        false => 'Suspended',
                    ])
                    ->label('Status')
                    ->default(true),
                Tables\Filters\SelectFilter::make('currentStudent.currentClassAssignment.class_id')
                    ->label('Class')
                    ->relationship('currentStudent.currentClassAssignment.class.className', 'name'),
                Tables\Filters\SelectFilter::make('roles')
                    ->label('Roles')
                    ->relationship('roles', 'name'),
                Tables\Filters\Filter::make('id')
                    ->label('Filter by IDs')
                    ->query(function (Builder $query, array $data): Builder {
                        if (array_key_exists('value', $data) && $data['value'] !== null && is_string($data['value'])) {
                            $ids = array_map('intval', explode(',', $data['value'])); // Explode and convert to integers
                            $ids = array_filter($ids); // Remove any zero or empty values
                            if (count($ids) > 0) {
                                $query->whereIn('id', $ids);
                            }
                        }
                        return $query;
                    })
                    ->form([
                        \Filament\Forms\Components\TextInput::make('value')
                            ->label('IDs (comma-separated)')
                            ->placeholder('e.g., 1,2,3,4')
                            ->helperText('Enter a comma-separated list of IDs'),
                    ])->columnSpanFull(),
            ], layout: FiltersLayout::AboveContent)
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
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function canViewAny(): bool
    {
        return Gate::allows('viewAny', GSuiteUser::class);
    }

    public static function canCreate(): bool
    {
        return Gate::allows('create', GSuiteUser::class);
    }

    public static function canView(Model $record): bool
    {
        return Gate::allows('view', $record->gSuiteUser);
    }

    public static function canEdit(Model $record): bool
    {
        return Gate::allows('update', $record->gSuiteUser);
    }

    public static function canDelete(Model $record): bool
    {
        return Gate::allows('delete', $record->gSuiteUser);
    }
}
