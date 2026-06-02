<?php

namespace App\Filament\Student\Resources\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AttendanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Attendance Record')
                    ->schema([
                        TextEntry::make('student.classAssignment.studentClass.name')
                            ->label('Class'),
                        TextEntry::make('student.classAssignment.studentSection.name')
                            ->label('Section'),
                        TextEntry::make('subject.name')
                            ->label('Subject')
                            ->placeholder('-'),
                        TextEntry::make('attendance_date')
                            ->label('Date')
                            ->date(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Attendance Status')
                    ->schema([
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'present' => 'success',
                                'absent' => 'danger',
                                'late' => 'warning',
                                'excused' => 'info',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn(string $state): string => ucfirst($state)),
                        TextEntry::make('remarks')
                            ->label('Remarks')
                            ->placeholder('No remarks')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Additional Information')
                    ->schema([
                        TextEntry::make('teacher.name')
                            ->label('Teacher')
                            ->placeholder('-'),
                        TextEntry::make('academic_year.name')
                            ->label('Academic Year')
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->label('Recorded At')
                            ->dateTime(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsed(),
            ]);
    }
}

