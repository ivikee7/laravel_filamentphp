<?php

namespace App\Filament\Admin\Resources\MessageTemplates;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use App\Models\User;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Admin\Resources\MessageTemplates\Pages\ListMessageTemplates;
use App\Filament\Admin\Resources\MessageTemplates\Pages\CreateMessageTemplate;
use App\Filament\Admin\Resources\MessageTemplates\Pages\ViewMessageTemplate;
use App\Filament\Admin\Resources\MessageTemplates\Pages\EditMessageTemplate;
use App\Filament\Admin\Resources\MessageTemplateResource\Pages;
use App\Filament\Admin\Resources\MessageTemplateResource\RelationManagers;
use App\Models\MessageTemplate;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MessageTemplateResource extends Resource
{
    protected static ?string $model = MessageTemplate::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'SMS Services';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    Select::make('sms_provider_id')
                        ->relationship('smsProvider', 'name', function ($query) {
                            $query->where('is_active', true);
                            return $query;
                        }),
                    TextInput::make('name')
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->maxLength(255),
                    Textarea::make('content')
                        ->label('Template')
                        ->hint('Use placeholders like {{name}}, {{date}}')
                        ->reactive()
                        ->helperText(fn($state) => strlen($state) . ' / 160 characters') // Live count
                        ->required()
                        ->columnSpanFull(),
                    Repeater::make('variables')
                        ->schema([
                            TextInput::make('name')
                                ->label('Variable Name')
                                ->required(),

                            Select::make('column')
                                ->label('Mapped User Field')
                                ->options(array_combine(
                                    (new User())->getFillable(),
                                    (new User())->getFillable()
                                ))
                                ->searchable()
                                ->required(),
                        ])
                        ->label('Expected Variables')
                        ->grid(2)
                        ->columnSpanFull()
                        ->columns(2),
                    Repeater::make('params')
                        ->schema([
                            TextInput::make('param_name')
                                ->label('Parameter Name')
                                ->required(),
                            TextInput::make('param_value')
                                ->label('Parameter Value')
                                ->required(),
                        ])
                        ->label('Additional Parameters')
                        ->grid(2)
                        ->columns(2)
                        ->columnSpanFull()
                        ->rules(['distinct:param_name']),
                    Toggle::make('is_active')
                        ->required()
                        ->inline(false),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('content')
                    ->wrap()
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('createdBy.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updatedBy.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                EditAction::make(),
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
            'index' => ListMessageTemplates::route('/'),
            'create' => CreateMessageTemplate::route('/create'),
            'view' => ViewMessageTemplate::route('/{record}'),
            'edit' => EditMessageTemplate::route('/{record}/edit'),
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
        return MessageTemplate::count();
    }
}
