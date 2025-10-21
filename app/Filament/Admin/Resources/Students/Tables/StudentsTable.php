<?php

namespace App\Filament\Admin\Resources\Students\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()->sortable(),
                ImageColumn::make('avatar')
                    ->disk('public')
                    ->visibility('public')
                    ->circular()
                    ->default(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name)),
                TextColumn::make('name')
                    ->searchable()->sortable()->wrap(),
                TextColumn::make('father_name')
                    ->searchable()->sortable()->wrap(),
                TextColumn::make('mother_name')
                    ->searchable()->sortable()->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.classAssignment.class.name')
                    ->searchable()->sortable()->wrap(),
                TextColumn::make('student.classAssignment.section.name')
                    ->searchable()->sortable()->wrap(),
                TextColumn::make('date_of_birth')
                    ->searchable()->sortable()->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.classAssignment.academicYear.name')
                    ->searchable()->sortable()->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('primary_contact_number')
                    ->searchable()->label('Primary contact'),
                TextColumn::make('secondary_contact_number')
                    ->searchable()->label('Secondary contact')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('full_address')
                    ->label('Address')
                    ->getStateUsing(function ($record) {
                        return collect([
                            $record->address,
                            $record->city,
                            $record->state,
                            $record->pin_code,
                        ])
                            ->filter() // Remove null/empty values
                            ->implode(', ');
                    })
                    ->wrap()
                    ->searchable(query: function ($query, string $search) {
                        $query->where(function ($q) use ($search) {
                            $q->where('address', 'like', "%{$search}%")
                                ->orWhere('city', 'like', "%{$search}%")
                                ->orWhere('state', 'like', "%{$search}%")
                                ->orWhere('pin_code', 'like', "%{$search}%");
                        });
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.quota.name')
                    ->searchable()->sortable()->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bloodGroup.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('gender.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Email'),
                TextColumn::make('gSuiteUser.email')->label('GSuite Email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('gSuiteUser.password')->label('GSuite Pwd')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('roles.name')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state ? 'Active' : 'Suspended')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Created At')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Updated At')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
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
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
