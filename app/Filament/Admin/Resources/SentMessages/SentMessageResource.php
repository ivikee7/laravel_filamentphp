<?php

namespace App\Filament\Admin\Resources\SentMessages;

use App\Filament\Admin\Resources\SentMessages\Pages\CreateSentMessage;
use App\Filament\Admin\Resources\SentMessages\Pages\EditSentMessage;
use App\Filament\Admin\Resources\SentMessages\Pages\ListSentMessages;
use App\Filament\Admin\Resources\SentMessages\Pages\ViewSentMessage;
use App\Filament\Admin\Resources\SentMessages\Schemas\SentMessageForm;
use App\Filament\Admin\Resources\SentMessages\Schemas\SentMessageInfolist;
use App\Filament\Admin\Resources\SentMessages\Tables\SentMessagesTable;
use App\Models\SentMessage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class SentMessageResource extends Resource
{
    protected static ?string $model = SentMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = "SMS Services";

    public static function form(Schema $schema): Schema
    {
        return SentMessageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SentMessageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SentMessagesTable::configure($table);
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
            'index' => ListSentMessages::route('/'),
            'create' => CreateSentMessage::route('/create'),
            'view' => ViewSentMessage::route('/{record}'),
            'edit' => EditSentMessage::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
