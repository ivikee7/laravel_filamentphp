<?php

namespace App\Filament\Admin\Resources\Registrations\Tables;

use App\Filament\Admin\Resources\Students\StudentResource;
use App\Filament\Exports\RegistrationExporter;
use App\Models\AcademicYear;
use App\Models\Registration;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable(),
                TextColumn::make('name')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('admissionClass.className.name')
                    ->label('Class')
                    ->sortable(),
                TextColumn::make('father_name')->wrap()
                    ->searchable(),
                TextColumn::make('date_of_birth')->wrap()
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('gender.name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('father_qualification')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('father_occupation')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('primary_contact_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('mother_name')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('mother_qualification')->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('mother_occupation')->wrap()
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
                TextColumn::make('previous_school')
                    ->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('payment_mode')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('payment_amount')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('payment_notes')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('previousClass.name')
                    ->label('Previous Class')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('updated_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)->wrap(),
                TextColumn::make('Admission')
                    ->badge()
                    ->getStateUsing(fn (Model $record): string => $record->student()->exists() ? 'Completed' : 'Pending')
                    ->color(fn (string $state): string => match ($state) {
                        'Completed' => 'success',
                        'Pending' => 'warning',
                        default => 'gray',
                    })
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('academic_year_id')
                    ->label('Academic Year')
                    ->relationship(
                        name: 'academicYear',
                        titleAttribute: 'name',
                        // Sort the filter options by ID descending
                        modifyQueryUsing: fn (Builder $query) => $query->orderBy('id', 'desc')
                    )
                    ->searchable() // Makes the filter dropdown searchable
                    ->preload()
                    ->default(function () {
                        return AcademicYear::where('is_active', true)->first()?->id;
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    Action::make('admission')
                        ->label('Admission')
                        ->hidden(fn($record) => $record->student()->exists())
                        ->url(fn(Registration $record) => StudentResource::getUrl('create', [
                            'registration_id' => $record->id, // Pass enquiry ID to Registration form
                        ]))->icon('heroicon-m-arrow-uturn-right')->color('success'),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    // Export
                    ExportBulkAction::make()
                        ->exporter(RegistrationExporter::class)
                        ->formats([
                            ExportFormat::Xlsx,
                            ExportFormat::Csv,
                        ])
                ]),
                BulkActionGroup::make([
                    ExportBulkAction::make('export-xlsx')
                        ->exporter(RegistrationExporter::class)
                        ->formats([
                            ExportFormat::Xlsx,
                        ])->label('Xlsx'),
                    ExportBulkAction::make('export-csv')
                        ->exporter(RegistrationExporter::class)
                        ->formats([
                            ExportFormat::Csv,
                        ])->label('CSV'),
                ])
                    ->label('Export'),
            ]);
    }
}
