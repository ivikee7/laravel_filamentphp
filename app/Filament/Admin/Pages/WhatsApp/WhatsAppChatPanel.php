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
    public $activeProviderId;
    public $activeContactName;
    public $newMessage;
    public string $newContactWaId = '';

    public function mount()
    {
        $this->contacts = WhatsAppMessage::select('to', 'whatsapp_provider_id')
            ->groupBy('to', 'whatsapp_provider_id')
            ->get()
            ->map(function ($contact) {
                $lastMessage = WhatsAppMessage::where('to', $contact->to)
                    ->where('whatsapp_provider_id', $contact->whatsapp_provider_id)
                    ->latest()
                    ->first();

                return (object) [
                    'wa_id' => $contact->to,
                    'provider_id' => $contact->whatsapp_provider_id,
                    'last_message' => $lastMessage->message ?? null,
                    'name' => $contact->to,
                ];
            });

        $this->messages = collect();
    }

    public function startNewChat()
    {
        $this->activeContactWaId = $this->newContactWaId;
        $this->activeContactName = $this->newContactWaId;
        $this->messages = collect();
    }

    public function selectContact($wa_id, $provider_id)
    {
        $this->activeContactWaId = $wa_id;
        $this->activeProviderId = $provider_id;
        $this->activeContactName = $wa_id;

        $this->messages = WhatsAppMessage::where('whatsapp_provider_id', $provider_id)
            ->where(function ($query) use ($wa_id) {
                $query->where('to', $wa_id)->orWhere('from_number', $wa_id);
            })
            ->orderBy('created_at')
            ->get();
    }

    public function sendMessage()
    {
        validator(['newMessage' => $this->newMessage], [
            'newMessage' => 'required|string',
        ])->validate();

        $provider = WhatsAppProvider::find($this->activeProviderId);

        if ($provider) {
            app(WhatsAppService::class)->sendMessage(
                $this->activeContactWaId,
                $this->newMessage,
                $provider
            );

            $this->newMessage = '';
            $this->selectContact($this->activeContactWaId, $this->activeProviderId);
        } else {
            Notification::make()
                ->title('Selected WhatsApp provider not found.')
                ->danger()
                ->send();
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(2)->schema([
                TextInput::make('to')->label('Recipient Number')->required()->tel(),
                Hidden::make('providerId')->default(WhatsAppProvider::first()?->id),
                Textarea::make('message')->label('Message')->rows(3)->required(),
            ]),
        ];
    }

    public function send()
    {
        $data = $this->form->getState();
        $provider = WhatsAppProvider::findOrFail($data['providerId']);

        $headers = collect($provider->headers)->pluck('header_value', 'header_name')->toArray();

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

        Notification::make()->title('Message sent!')->success()->send();

        $this->reset('message');
    }

    public function getChatHistory($page = 1)
    {
        return WhatsAppMessage::where('to', $this->activeContactWaId)
            ->orWhere('from_number', $this->activeContactWaId)
            ->orderBy('created_at', 'desc')
            ->paginate(20, ['*'], 'page', $page);
    }

    public function getContactsProperty()
    {
        return WhatsAppMessage::selectRaw('wa_id, provider_id, MAX(created_at) as last_time')
            ->whereNotNull('wa_id')
            ->groupBy('wa_id', 'provider_id')
            ->orderByDesc('last_time')
            ->get()
            ->map(function ($chat) {
                $lastMessage = WhatsAppMessage::where('wa_id', $chat->wa_id)
                    ->where('provider_id', $chat->provider_id)
                    ->latest()
                    ->first();

                $provider = WhatsAppProvider::find($chat->provider_id);

                return (object) [
                    'wa_id' => $chat->wa_id,
                    'provider_id' => $chat->provider_id,
                    'last_message' => $lastMessage?->message ?? '',
                    'name' => optional(User::where('primary_contact_number', $chat->wa_id)->first())->name,
                    'provider_name' => $provider?->name ?? 'Unknown',
                ];
            });
    }
}
