<?php

namespace App\Filament\Admin\Resources\SmsProviderResource\Pages;

use App\Filament\Admin\Resources\SmsProviderResource;
use App\Models\SmsProvider;
use App\Models\User;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class SendBulkSms extends ListRecords
{
    protected static string $resource = SmsProviderResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query())
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Name')->sortable(),
                Tables\Columns\TextColumn::make('father_contact_number')->label('Phone'),
            ])
            ->actions([
                Action::make('sendSms')
                    ->label('Send SMS')
                    ->icon('heroicon-o-paper-airplane')
                    ->form([
                        Select::make('provider')
                            ->label('SMS Provider')
                            ->options(SmsProvider::where('is_active', true)->pluck('name', 'id'))
                            ->required(),
                        Textarea::make('message')->label('Message')->required(),
                    ])
                    ->action(fn(User $record, array $data) => $this->sendSms($record->phone, $data['message'], $data['provider']))
                    ->color('success'),
            ])
            ->bulkActions([
                BulkAction::make('sendBulkSms')
                    ->label('Send Bulk SMS')
                    // ->icon('heroicon-o-chat-alt-2')
                    ->form([
                        Select::make('provider')
                            ->label('SMS Provider')
                            ->options(SmsProvider::where('is_active', true)->pluck('name', 'id'))
                            ->required(),
                        Textarea::make('message')->label('Message')->required(),
                    ])
                    ->action(fn($records, array $data) => $this->sendBulkSms($records, $data['message'], $data['provider']))
                    ->color('warning'),
            ]);
    }

    public function sendSms($phone, $message, $providerId)
    {
        $provider = SmsProvider::findOrFail($providerId);

        $response = Http::post($provider->api_url, [
            'api_key' => $provider->api_key,
            'sender' => $provider->sender_id,
            'to' => $phone,
            'message' => $message,
        ]);

        return $response->successful();
    }

    public function sendBulkSms($records, $message, $providerId)
    {
        $provider = SmsProvider::findOrFail($providerId);

        foreach ($records as $user) {
            Http::post($provider->api_url, [
                'api_key' => $provider->api_key,
                'sender' => $provider->sender_id,
                'to' => $user->phone,
                'message' => "Hello {$user->name}, $message",
            ]);
        }
    }
}
