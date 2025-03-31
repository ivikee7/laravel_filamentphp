<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Collection;

class MinimalTest extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.minimal-test';

    public Collection $testData;

    public function mount(): void
    {
        $this->testData = collect([
            ['id' => 1, 'name' => 'Test User 1'],
            ['id' => 2, 'name' => 'Test User 2'],
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return $this->testData;
            })
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name'),
            ])
            ->paginated(false);
    }
}
