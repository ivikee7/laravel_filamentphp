<?php

namespace App\Filament\Admin\Resources\Transport\TransportAssignments;

use App\Filament\Admin\Resources\Transport\TransportAssignments\Pages\CreateTransportAssignment;
use App\Filament\Admin\Resources\Transport\TransportAssignments\Pages\EditTransportAssignment;
use App\Filament\Admin\Resources\Transport\TransportAssignments\Pages\ListTransportAssignments;
use App\Filament\Admin\Resources\Transport\TransportAssignments\Pages\ViewTransportAssignment;
use App\Filament\Admin\Resources\Transport\TransportAssignments\Schemas\TransportAssignmentForm;
use App\Filament\Admin\Resources\Transport\TransportAssignments\Schemas\TransportAssignmentInfolist;
use App\Filament\Admin\Resources\Transport\TransportAssignments\Tables\TransportAssignmentsTable;
use App\Models\TransportAssignment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TransportAssignmentResource extends Resource
{
    protected static ?string $model = TransportAssignment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    protected static string | UnitEnum | null $navigationGroup = 'Transport';

    public static function form(Schema $schema): Schema
    {
        return TransportAssignmentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TransportAssignmentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransportAssignmentsTable::configure($table);
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
            'index' => ListTransportAssignments::route('/'),
            'create' => CreateTransportAssignment::route('/create'),
            'view' => ViewTransportAssignment::route('/{record}'),
            'edit' => EditTransportAssignment::route('/{record}/edit'),
        ];
    }
}
