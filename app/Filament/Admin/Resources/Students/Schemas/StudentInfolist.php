<?php

namespace App\Filament\Admin\Resources\Students\Schemas;

use App\Models\Student;
use App\Models\User;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Password;

class StudentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Student info')
                    ->schema([
                        Group::make()
                            ->schema([
                                ImageEntry::make('avatar')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->imageSize(150)
                                    ->circular()
                                    ->alignCenter()
                                    ->hiddenLabel()
                                    ->default(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name)),
                                Group::make()
                                    ->schema([
                                        TextEntry::make('name')->label('Student name'),
                                        TextEntry::make('is_active')
                                            ->label('Status')
                                            ->badge() // Display as a colored badge
                                            ->color(fn (string $state): string => match ($state) {
                                                '0' => 'danger',
                                                '1' => 'success',
                                                default => 'gray',
                                            })
                                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                                '0' => 'Suspended',
                                                '1' => 'Active',
                                                default => 'Unknown',
                                            }),
                                        TextEntry::make('gSuiteUser.email'),
                                        TextEntry::make('gSuiteUser.password'),
                                    ])->columns(2)
                            ])->columns(2),
                        Group::make()
                            ->schema([
                                TextEntry::make('date_of_birth')
                                    ->date(),
                                TextEntry::make('gender.name')->label('Gender'),
                                TextEntry::make('bloodGroup.name')->label('Blood Group'),
                            ])->columns(4),
                    ])
                    ->columnSpanFull(),
                Section::make('Admission Info')
                    ->schema([
                        TextEntry::make('student.quota.name')
                            ->label('Quota'),
                        TextEntry::make('student.classAssignment.academicYear.name')
                            ->label('Academic Year'),
                        TextEntry::make('student.classAssignment.studentClass.name')
                            ->label('Class'),
                        TextEntry::make('student.classAssignment.studentSection.name')
                            ->label('Section'),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make('Parents Info')
                    ->schema([
                        TextEntry::make('father_name')->label('Fathers name'),
                        TextEntry::make('mother_name')->label('Mothers name'),
                        TextEntry::make('student.localGuardian.name')
                            ->label('Guardian name')
                            ->placeholder('-'),
                        TextEntry::make('student.local_guardian_relationship')
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Contact Info')
                    ->schema([
                        TextEntry::make('primary_contact_number')
                            ->label('Primary contact number'),
                        TextEntry::make('secondary_contact_number')
                            ->label('Secondary contact number'),
                        TextEntry::make('email')->label('Email'),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make('Address')
                    ->schema([
                        TextEntry::make('address'),
                        TextEntry::make('city'),
                        TextEntry::make('state'),
                        TextEntry::make('pin_code')
                            ->label('Pin code'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Other Info')
                    ->schema([
                        TextEntry::make('student.current_status')
                            ->badge(),
                        TextEntry::make('student.tc_status')
                            ->badge(),
                        TextEntry::make('student.leaving_date')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('student.exit_reason')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make('Record creation info')
                    ->schema([
                        TextEntry::make('createdBy.name')
                            ->label('Created by')
                            ->placeholder('-'),
                        TextEntry::make('updatedBy.name')
                            ->label('Updated by')
                            ->placeholder('-'),
                        TextEntry::make('deletedBy.name')
                            ->label('Deleted by')
                            ->placeholder('-')
                            ->visible(fn(User $record): bool => $record->trashed()),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->placeholder('-')
                            ->visible(fn(User $record): bool => $record->trashed()),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
