<?php

namespace App\Filament\Student\Resources\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentProfileInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Information')
                    ->schema([
                        Group::make()
                            ->schema([
                                ImageEntry::make('avatar')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->imageSize(200)
                                    ->circular()
                                    ->hiddenLabel()
                                    ->default(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name))
                                    ->alignCenter(),
                                Group::make()
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Full Name')
                                            ->size('lg')
                                            ->weight('bold'),
                                        TextEntry::make('email')
                                            ->label('Email')
                                            ->copyable(),
                                        TextEntry::make('is_active')
                                            ->label('Account Status')
                                            ->badge()
                                            ->color(fn(string $state): string => $state ? 'success' : 'danger')
                                            ->formatStateUsing(fn(string $state): string => $state ? 'Active' : 'Suspended'),
                                    ]),
                            ])
                            ->columns(2),
                        Group::make()
                            ->schema([
                                TextEntry::make('date_of_birth')
                                    ->label('Date of Birth')
                                    ->date(),
                                TextEntry::make('gender.name')
                                    ->label('Gender'),
                                TextEntry::make('bloodGroup.name')
                                    ->label('Blood Group'),
                            ])
                            ->columns(3),
                    ])
                    ->columnSpanFull(),

                Section::make('Academic Information')
                    ->schema([
                        TextEntry::make('student.classAssignment.academicYear.name')
                            ->label('Current Academic Year'),
                        TextEntry::make('student.classAssignment.studentClass.name')
                            ->label('Current Class'),
                        TextEntry::make('student.classAssignment.studentSection.name')
                            ->label('Current Section'),
                        TextEntry::make('student.quota.name')
                            ->label('Quota')
                            ->placeholder('-'),
                        TextEntry::make('student.admission_number')
                            ->label('Admission Number')
                            ->placeholder('-'),
                        TextEntry::make('student.registration_id')
                            ->label('Registration ID')
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Contact Information')
                    ->schema([
                        Group::make()
                            ->schema([
                                TextEntry::make('primary_contact_number')
                                    ->label('Primary Contact'),
                                TextEntry::make('secondary_contact_number')
                                    ->label('Secondary Contact')
                                    ->placeholder('-'),
                                TextEntry::make('email')
                                    ->label('Email Address'),
                            ])
                            ->columns(3),
                        Group::make()
                            ->schema([
                                TextEntry::make('address')
                                    ->label('Address'),
                                TextEntry::make('city')
                                    ->label('City'),
                                TextEntry::make('state')
                                    ->label('State'),
                                TextEntry::make('pin_code')
                                    ->label('Pin Code'),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),

                Section::make('Parents/Guardian Information')
                    ->schema([
                        TextEntry::make('father_name')
                            ->label('Father\'s Name'),
                        TextEntry::make('mother_name')
                            ->label('Mother\'s Name'),
                        TextEntry::make('student.localGuardian.name')
                            ->label('Local Guardian')
                            ->placeholder('-'),
                        TextEntry::make('student.local_guardian_relationship')
                            ->label('Guardian Relationship')
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Enrollment Status')
                    ->schema([
                        TextEntry::make('student.current_status')
                            ->label('Current Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'active' => 'success',
                                'suspended' => 'warning',
                                'graduated' => 'info',
                                'withdrawn' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn(string $state): string => ucfirst(str_replace('_', ' ', $state))),
                        TextEntry::make('student.tc_status')
                            ->label('TC Status')
                            ->badge()
                            ->placeholder('-'),
                        TextEntry::make('student.leaving_date')
                            ->label('Leaving Date')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('student.exit_reason')
                            ->label('Exit Reason')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('GSuite Account')
                    ->schema([
                        TextEntry::make('gSuiteUser.email')
                            ->label('GSuite Email')
                            ->placeholder('Not created yet'),
                        TextEntry::make('gSuiteUser.password')
                            ->label('GSuite Password')
                            ->placeholder('Not available'),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsed(),

                Section::make('Record Metadata')
                    ->schema([
                        TextEntry::make('createdBy.name')
                            ->label('Created By')
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),
                        TextEntry::make('updatedBy.name')
                            ->label('Updated By')
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsed(),
            ]);
    }
}

