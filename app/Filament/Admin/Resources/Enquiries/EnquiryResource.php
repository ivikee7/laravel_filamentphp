<?php

namespace App\Filament\Admin\Resources\Enquiries;

use App\Filament\Admin\Resources\Enquiries\Pages\CreateEnquiry;
use App\Filament\Admin\Resources\Enquiries\Pages\EditEnquiry;
use App\Filament\Admin\Resources\Enquiries\Pages\ListEnquiries;
use App\Filament\Admin\Resources\Enquiries\Pages\ViewEnquiry;
use App\Filament\Admin\Resources\Enquiries\Schemas\EnquiryForm;
use App\Filament\Admin\Resources\Enquiries\Schemas\EnquiryInfolist;
use App\Filament\Admin\Resources\Enquiries\Tables\EnquiriesTable;
use App\Filament\Admin\Resources\Enquiries\Widgets\EnquiryWidget;
use App\Models\Enquiry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnquiryResource extends Resource
{
    protected static ?string $model = Enquiry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return EnquiryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EnquiryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EnquiriesTable::configure($table);
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

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return Enquiry::count();
    }
}
