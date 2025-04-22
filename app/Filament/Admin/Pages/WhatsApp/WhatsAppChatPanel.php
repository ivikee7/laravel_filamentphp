<?php

namespace App\Filament\Admin\Pages\WhatsApp;

use App\Models\WhatsAppMessage;
use App\Models\WhatsAppProvider;
use App\Services\WhatsApp\WhatsAppService;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WhatsAppChatPanel extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.whats-app.whats-app-chat-panel';

    protected static ?string $navigationLabel = 'Live Chat';


    public $contacts;
    public $messages;
    public $activeContactWaId;
    public $activeContactName;
    public $newMessage;
    public string $newContactWaId = '';

    public function mount()
    {
        // Initialize contacts
        $this->contacts = WhatsAppMessage::select('to as wa_id')
            ->groupBy('to')
            ->get()
            ->map(function ($contact) {
                $lastMessage = WhatsAppMessage::where('to', $contact->wa_id)
                    ->latest()
                    ->first();

                return (object)[
                    'wa_id' => $contact->wa_id,
                    'last_message' => $lastMessage->message ?? null,
                    'name' => $contact->wa_id, // You can customize this with real names if available
                ];
            });

        $this->messages = collect(); // Initialize empty messages collection
    }

    public function startNewChat()
    {
        $this->activeContactWaId = $this->newContactWaId;
        $this->activeContactName = $this->newContactWaId; // You can replace with real name if available
        $this->messages = collect(); // Empty initial messages
    }

    public function selectContact($wa_id)
    {
        $this->activeContactWaId = $wa_id;
        $this->activeContactName = $wa_id; // Replace with actual name if available

        // Load the message history for the selected contact
        $this->messages = WhatsAppMessage::where('to', $wa_id)
            ->orWhere('from_number', $wa_id)
            ->orderBy('created_at')
            ->get();
    }

    public function sendMessage()
    {
        validator(['newMessage' => $this->newMessage], [
            'newMessage' => 'required|string',
        ])->validate();


        // Get default provider
        $provider = WhatsAppProvider::where('is_default', true)->first();

        if ($provider) {
            // Send message through WhatsAppService
            app(WhatsAppService::class)->sendMessage(
                $this->activeContactWaId,
                $this->newMessage,
                $provider
            );

            // Clear input and refresh chat
            $this->newMessage = '';
            $this->selectContact($this->activeContactWaId);
        } else {
            Notification::make()
                ->title('Default WhatsApp provider not found.')
                ->danger()
                ->send();
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(2)->schema([
                TextInput::make('to')
                    ->label('Recipient Number')
                    ->required()
                    ->tel(),

                Hidden::make('providerId')
                    ->default(WhatsAppProvider::first()?->id), // Default provider

                Textarea::make('message')
                    ->label('Message')
                    ->rows(3)
                    ->required(),
            ]),
        ];
    }

    public function send()
    {
        $data = $this->form->getState();

        $provider = WhatsAppProvider::findOrFail($data['providerId']);

        $headers = collect($provider->headers)
            ->pluck('header_value', 'header_name')
            ->toArray();

        $response = Http::withHeaders($headers)
            ->withToken($provider->token)
            ->post($provider->base_url . $provider->send_message_endpoint, [
                'messaging_product' => 'whatsapp',
                'to' => '91' . $data['to'],
                'type' => 'text',
                'text' => [
                    'body' => $data['message'],
                ],
            ]);

        WhatsAppMessage::create([
            'whatsapp_provider_id' => $provider->id,
            'to' => $data['to'],
            'message' => $data['message'],
            'status' => $response->successful() ? 'sent' : 'failed',
            'response' => $response->body(),
            'created_by' => Auth::id(),
        ]);

        Notification::make()
            ->title('Message sent!')
            ->success()
            ->send();

        $this->reset('message');
    }

    public function getChatHistory()
    {
        return WhatsAppMessage::where('to', $this->activeContactWaId)
            ->orWhere('from', $this->activeContactWaId)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
    }
}
