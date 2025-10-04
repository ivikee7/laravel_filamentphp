<?php

namespace App\Filament\Admin\Resources\MessageTemplates;

use App\Filament\Admin\Resources\MessageTemplates\Pages\CreateMessageTemplate;
use App\Filament\Admin\Resources\MessageTemplates\Pages\EditMessageTemplate;
use App\Filament\Admin\Resources\MessageTemplates\Pages\ListMessageTemplates;
use App\Filament\Admin\Resources\MessageTemplates\Pages\ViewMessageTemplate;
use App\Filament\Admin\Resources\MessageTemplates\Schemas\MessageTemplateForm;
use App\Filament\Admin\Resources\MessageTemplates\Schemas\MessageTemplateInfolist;
use App\Filament\Admin\Resources\MessageTemplates\Tables\MessageTemplatesTable;
use App\Models\MessageTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class MessageTemplateResource extends Resource
{
    protected static ?string $model = MessageTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = "SMS Services";

    public static function form(Schema $schema): Schema
    {
        return MessageTemplateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MessageTemplateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MessageTemplatesTable::configure($table);
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

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return MessageTemplate::count();
    }
}
