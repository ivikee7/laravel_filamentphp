<?php

namespace App\Filament\Admin\Resources\Website\Enquiries;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Admin\Resources\Website\Enquiries\Pages\ListEnquiries;
use App\Filament\Admin\Resources\Website\Enquiries\Pages\CreateEnquiry;
use App\Filament\Admin\Resources\Website\Enquiries\Pages\ViewEnquiry;
use App\Filament\Admin\Resources\Website\Enquiries\Pages\EditEnquiry;
use App\Filament\Admin\Resources\Website\EnquiryResource\Pages;
use App\Filament\Admin\Resources\Website\EnquiryResource\RelationManagers;
use App\Models\WebsiteEnquiry;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Livewire\wrap;

class EnquiryResource extends Resource
{
    protected static ?string $model = WebsiteEnquiry::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Website Enquiry';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->maxLength(50)
                    ->default(null)
                    ->disabled()
                    ->required(),
                TextInput::make('contact_number')
                    ->numeric()
                    ->rules(['digits:10'])
                    ->default(null)
                    ->disabled()
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->maxLength(50)
                    ->default(null)
                    ->disabled()
                    ->required(),
                TextInput::make('message')
                    ->maxLength(255)
                    ->default(null)
                    ->disabled()
                    ->required(),
                Textarea::make('notes')
                    ->label('Follow-up notes')
                    ->maxLength(150)
                    ->default(null)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('name')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('contact_number')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('message')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('notes')
                    ->label('Follow-up notes')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('created_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
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
            'index' => ListEnquiries::route('/'),
            'create' => CreateEnquiry::route('/create'),
            'view' => ViewEnquiry::route('/{record}'),
            'edit' => EditEnquiry::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return WebsiteEnquiry::count();
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
