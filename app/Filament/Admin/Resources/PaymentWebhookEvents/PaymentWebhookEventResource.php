<?php

namespace App\Filament\Admin\Resources\PaymentWebhookEvents;

use App\Filament\Admin\Resources\PaymentWebhookEvents\Pages;
use App\Models\PaymentWebhookEvent;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentWebhookEventResource extends Resource
{
    protected static ?string $model = PaymentWebhookEvent::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::Signal;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('driver')->badge()->sortable(),
                TextColumn::make('event_id')->label('Event #')->toggleable(),
                TextColumn::make('payment_reference')->label('Reference')->searchable(),
                TextColumn::make('signature_valid')->badge()->formatStateUsing(fn (bool $state): string => $state ? 'valid' : 'invalid')
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('attempts')->sortable(),
                TextColumn::make('processed_at')->since()->placeholder('-'),
                TextColumn::make('created_at')->since()->label('Received'),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentWebhookEvents::route('/'),
            'view' => Pages\ViewPaymentWebhookEvent::route('/{record}'),
        ];
    }
}

