<?php

namespace App\Filament\Admin\Resources\AcademicYears\Resources\StudentClasses\Resources\StudentSections\Resources\StudentClassAssignments\Tables;

use App\Filament\Admin\Resources\Students\StudentResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class StudentClassAssignmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.user.id')
                    ->searchable()
                    ->sortable()
                    ->label('id')
                    ->toggleable(isToggledHiddenByDefault: false),
                ImageColumn::make('student.user.avatar')
                    ->disk('public')
                    ->visibility('public')
                    ->circular()
                    ->default(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name))
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('student.user.name')
                    ->searchable()->sortable()->wrap(),
                TextColumn::make('student.user.father_name')
                    ->searchable()->sortable()->wrap()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('student.user.mother_name')
                    ->searchable()->sortable()->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('class.name')
                    ->searchable()->sortable()->wrap()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('section.name')
                    ->searchable()->sortable()->wrap()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('student.user.date_of_birth')
                    ->searchable()->sortable()->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('academicYear.name')
                    ->searchable()->sortable()->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.user.primary_contact_number')
                    ->searchable()->label('Primary contact')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('student.user.secondary_contact_number')
                    ->searchable()->label('Secondary contact')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('full_address')
                    ->label('Address')
                    ->getStateUsing(function ($record) {
                        return collect([
                            $record->student->user->address,
                            $record->student->user->city,
                            $record->student->user->state,
                            $record->student->user->pin_code,
                        ])
                            ->filter() // Remove null/empty values
                            ->implode(', ');
                    })
                    ->wrap()
                    ->searchable(query: function ($query, string $search) {
                        $query->student->user->where(function ($q) use ($search) {
                            $q->where('address', 'like', "%{$search}%")
                                ->orWhere('city', 'like', "%{$search}%")
                                ->orWhere('state', 'like', "%{$search}%")
                                ->orWhere('pin_code', 'like', "%{$search}%");
                        });
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.quota.name')
                    ->searchable()->sortable()->wrap()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('student.user.bloodGroup.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.user.gender.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.user.email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Email'),
                TextColumn::make('student.user.gSuiteUser.email')->label('GSuite Email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.user.gSuiteUser.password')->label('GSuite Pwd')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.user.roles.name')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.user.is_active')
                    ->label('Status')
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state ? 'Active' : 'Suspended')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('student.user.created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Created At')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.user.updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Updated At')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.user.deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Deleted At')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record): string => StudentResource::getUrl('view', ['record' => $record->student->user->id])),
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
