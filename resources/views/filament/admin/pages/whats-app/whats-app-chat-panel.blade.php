<x-filament-panels::page>

    <div class="flex h-[80vh] rounded-xl overflow-hidden shadow-lg bg-white dark:bg-gray-900">

        {{-- Sidebar --}}
        <div x-data="{ open: true }" class="flex-shrink-0 flex">
            <!-- Toggle -->
            <button @click="open = !open"
                class="p-2 bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white transition">
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <div x-show="open" x-transition class="w-72 border-r bg-gray-100 dark:bg-gray-800 flex flex-col">
                <div class="p-4 border-b dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Chats</h2>
                </div>

                <div class="flex-1 overflow-y-auto">
                    @forelse($contacts as $contact)
                        <div wire:click="selectContact('{{ $contact->wa_id }}')"
                            class="p-4 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                            <div class="font-semibold text-gray-800 dark:text-white">
                                {{ $contact->name ?? $contact->wa_id }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                {{ Str::limit($contact->last_message, 40) }}
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                            No contacts yet
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Main Chat Area --}}
        <div class="flex-1 flex flex-col bg-chat-bg dark:bg-gray-900">
            {{-- Header --}}
            <div class="px-6 py-4 bg-gray-200 dark:bg-gray-800 border-b dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white">
                    {{ $activeContactName ?? 'Select a contact' }}
                </h2>
            </div>

            {{-- Messages --}}
            <div class="flex-1 p-6 overflow-y-auto space-y-4">
                @foreach ($messages as $message)
                    <div class="flex {{ $message->direction === 'outgoing' ? 'justify-end' : 'justify-start' }}">
                        <div
                            class="max-w-md px-4 py-2 rounded-xl shadow
                            {{ $message->direction === 'outgoing'
                                ? 'bg-green-500 text-white rounded-br-none'
                                : 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-bl-none' }}">
                            <p class="text-sm leading-snug">{{ $message->message }}</p>
                            <div class="text-xs mt-1 opacity-60 text-right">
                                {{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Message Input --}}
            @if ($activeContactWaId)
                <form wire:submit.prevent="sendMessage"
                    class="p-4 border-t dark:border-gray-700 bg-gray-100 dark:bg-gray-800">
                    <div class="flex items-center gap-3">
                        <input type="text" wire:model.defer="newMessage" placeholder="Type a message..."
                            class="flex-1 px-4 py-2 rounded-full bg-white dark:bg-gray-700 border dark:border-gray-600 dark:text-white focus:ring focus:ring-primary-500 focus:outline-none">

                        <button type="submit"
                            class="px-4 py-2 rounded-full bg-primary-600 hover:bg-primary-700 text-white font-semibold">
                            Send
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

</x-filament-panels::page>
