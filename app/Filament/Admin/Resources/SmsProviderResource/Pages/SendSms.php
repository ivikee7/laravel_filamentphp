<?php

namespace App\Filament\Admin\Resources\SmsProviderResource\Pages;

use App\Filament\Admin\Resources\SmsProviderResource;
use App\Jobs\SendSmsJob;
use App\Models\MessageTemplate;
use App\Models\SmsProvider;
use Filament\Resources\Pages\Page;
use App\Models\User;
use App\Services\MessagingService;
use App\Services\SMSService;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class SendSms extends Page
{
    protected static string $resource = SmsProviderResource::class;

    protected static string $view = 'filament.admin.resources.sms-provider-resource.pages.send-sms';

    public ?array $users = [];
    public ?string $message = '';
    public ?string $provider_id = '';

    public ?string $phoneNumber = null;
    public ?string $template_id = null;
    public array $variables = [];

    protected function getFormSchema(): array
    {
        return [
            Section::make('')
                ->schema([
                    Section::make('')
                        ->schema([
                            Select::make('provider_id')
                                ->label('Select SMS Provider')
                                ->options(
                                    SmsProvider::where('is_active', true)->pluck('name', 'id')
                                )
                                ->searchable()
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $set('template_id', null);  // Reset template
                                    $set('message', null);      // Reset message preview
                                }),

                            Select::make('template_id')
                                ->label('Message Template')
                                ->options(function (callable $get) {
                                    $providerId = $get('provider_id');
                                    if (!$providerId) {
                                        return [];
                                    }

                                    return MessageTemplate::where('is_active', true)
                                        ->where('sms_provider_id', $providerId)
                                        ->pluck('name', 'id');
                                })
                                ->searchable()
                                ->reactive()
                                ->required()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $this->loadTemplateVariables($state);
                                }),
                        ])
                        ->columns(2),

                    Select::make('users')
                        ->label('Select Users')
                        ->multiple()
                        ->searchable()
                        ->options(User::pluck('name', 'id'))
                        ->reactive()
                        ->afterStateUpdated(fn($state) => $this->loadUserPhone($state))
                        ->required(),

                    Section::make('')
                        ->schema([
                            Textarea::make('message')
                                ->label('Message Preview')
                                ->reactive()
                                ->disabled()
                                ->dehydrated(false)
                                ->live(),
                        ]),
                ]),
        ];
    }

    public function loadUserPhone($userIds)
    {
        // Optional: You can populate phone numbers if needed
    }

    public function loadTemplateVariables($templateId)
    {
        $template = MessageTemplate::find($templateId);

        if ($template) {
            $this->variables = collect($template->variables ?? [])
                ->mapWithKeys(fn($var) => [$var['name'] => $var['column'] ?? null])
                ->toArray();

            $this->form->fill([
                'message' => $template->content,
            ]);

            $this->dispatch('formUpdated');
        }
    }

    public function updateMessagePreview()
    {
        $template = MessageTemplate::find($this->template_id);
        if (!$template) return;

        $message = $template->content;
        $firstUser = User::find($this->users[0] ?? null);

        foreach ($this->variables as $key => $column) {
            if ($firstUser && $column && isset($firstUser->{$column})) {
                $message = str_replace('{{' . $key . '}}', $firstUser->{$column}, $message); // ✅ FIXED
            }
        }

        $this->form->fill(['message' => $message]);
        $this->dispatch('refresh');
    }

    public function send()
    {
        if (!$this->provider_id) {
            Notification::make()->title('Select an SMS Provider')->danger()->send();
            return;
        }

        if (empty($this->users)) {
            Notification::make()->title('Select at least one user')->danger()->send();
            return;
        }

        $template = MessageTemplate::find($this->template_id);

        if (!$template) {
            Notification::make()->title('Template not found')->danger()->send();
            return;
        }

        try {
            $provider = new SMSService($this->provider_id);

            foreach ($this->users as $userId) {
                $user = User::find($userId);
                if (!$user) continue;

                $message = $template->content;

                foreach ($this->variables as $key => $column) {
                    if ($column && isset($user->{$column})) {
                        $message = str_replace('{{' . $key . '}}', $user->{$column}, $message); // ✅ FIXED
                    }
                }

                MessagingService::send($user->primary_contact_number, $message, $provider, $template);

                // SendSmsJob::dispatch($user->primary_contact_number, $message, $provider, $template);

                // $provider->sendSms($user->primary_contact_number, $message, $template);
            }

            Notification::make()->title('SMS sent successfully!')->success()->send();
        } catch (\Exception $e) {
            Notification::make()->title('Error: ' . $e->getMessage())->danger()->send();
        }
    }
}
