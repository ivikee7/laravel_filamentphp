<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Filament\Resources\AttendanceResource\RelationManagers\AttendanceRelationManager;
use App\Models\Attendance;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Attendance Management System';
    protected static ?string $modelLabel = 'Daily Attendance';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('attendance.date'),
                Forms\Components\DateTimePicker::make('attendance.in'),
                Forms\Components\DateTimePicker::make('attendance.out'),
                Forms\Components\DateTimePicker::make('attendance.entred_in_bus'),
                Forms\Components\DateTimePicker::make('attendance.exit_from_bus'),
                Forms\Components\Toggle::make('attendance.is_absent')
                    ->required()
                    ->inline(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('attendance.date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('attendance.entred_in_bus')
                    ->label('Entred In Bus')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('attendance.in')
                    ->label('In')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('attendance.out')
                    ->label('Out')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('attendance.exit_from_bus')
                    ->label('Exit From Bus')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('attendance.is_absent')
                    ->label('Absent')
                    ->boolean(),
                Tables\Columns\TextColumn::make('attendance.creator_id')
                    ->label('Created By')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('attendance.updater_id')
                    ->label('Updated By')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('attendance.created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('attendance.updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('attendance.deleted_at')
                    ->label('Deleted At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'monthly-attendance' => Pages\MonthlyAttendance::route('/monthly-attendance'),
            'view' => Pages\ViewAttendance::route('/{record}'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
