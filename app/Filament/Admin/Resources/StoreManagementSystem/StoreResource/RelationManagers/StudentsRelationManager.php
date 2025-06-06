<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\StoreResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getStudentsQuery())
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->size(50)
                    ->label('Image')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->label('Name')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('father_name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->label('Father Name')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('mother_name')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->label('Motner Name')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('currentStudent.currentClassAssignment.class.className.name')
                    ->searchable()
                    ->sortable()
                    ->label('Class')
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\Action::make('products')
                    ->label('Products')
                    ->icon('heroicon-o-shopping-cart')
                    ->url(fn ($record) => route('filament.admin.resources.store-management-system.products.index', [
                        'student_id' => $record->id,
                    ]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }

    protected function getStudentsQuery(): Builder
    {
        $store = $this->getOwnerRecord(); // current Store instance

        return User::role('student')
            ->whereHas('student.currentClassAssignment.class.className.products', function ($query) use ($store) {
                $query->where('store_id', $store->id);
            })
            ->whereHas('student.user', function ($query) {
                $query->where('is_active', true);
            })->whereHas('student.currentClassAssignment.academicYear', function ($query) {
                $query->where('is_active', true);
            });
    }
}
