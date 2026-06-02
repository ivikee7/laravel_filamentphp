<?php

namespace App\Filament\Admin\Resources\PaymentGateways;

use App\Filament\Admin\Resources\PaymentGateways\Pages;
use App\Models\PaymentGateway;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PaymentGatewayResource extends Resource
{
    protected static ?string $model = PaymentGateway::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::CreditCard;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(255),
            Select::make('driver')
                ->required()
                ->options([
                    'cash' => 'Cash',
                    'bank_transfer' => 'Bank Transfer',
                    'upi' => 'UPI',
                    'razorpay' => 'Razorpay',
                    'stripe' => 'Stripe',
                ]),
            Toggle::make('is_enabled')->default(true),
            Toggle::make('is_default')->default(false),
            KeyValue::make('config')->keyLabel('Config Key')->valueLabel('Value')->columnSpanFull(),
            KeyValue::make('meta')->keyLabel('Meta Key')->valueLabel('Value')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                TextColumn::make('driver')->badge()->sortable(),
                ToggleColumn::make('is_enabled'),
                ToggleColumn::make('is_default'),
                TextColumn::make('updated_at')->since()->label('Updated'),
            ])
            ->defaultSort('is_default', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentGateways::route('/'),
            'create' => Pages\CreatePaymentGateway::route('/create'),
            'edit' => Pages\EditPaymentGateway::route('/{record}/edit'),
        ];
    }
}

