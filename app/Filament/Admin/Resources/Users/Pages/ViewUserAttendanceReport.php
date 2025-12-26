<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ViewUserAttendanceReport extends Page implements HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;

    protected static string $resource = UserResource::class;

    protected string $view = 'filament.admin.resources.users.pages.view-user-attendance-report';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function table(Table $table): Table
    {
        return $table->query(function () {
            return $this->record->attendances();
        })->columns([
            TextColumn::make('id')->label(__('#')),
            TextColumn::make('user.id')->label(__('ID')),
            TextColumn::make('user.name')->label(__('Name')),
            TextColumn::make('created_at')->label(__('Created At'))
                ->searchable(true),
            TextColumn::make('type')->label(__('Type'))->searchable(true),
        ])
            ->defaultSort('created_at', 'desc');
    }
}
