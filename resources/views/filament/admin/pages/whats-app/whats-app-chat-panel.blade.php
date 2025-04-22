<x-filament-panels::page>

    <div class="flex h-[80vh] border rounded-xl overflow-hidden shadow-lg bg-white dark:bg-gray-900">
        {{-- Sidebar: Contact List --}}
        <div class="w-64 border-r dark:border-gray-800 bg-gray-100 dark:bg-gray-800">
            <div class="p-4 border-b dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Contacts</h2>
            </div>

            <div class="overflow-y-auto h-full">
                @forelse($contacts as $contact)
                    <div wire:click="selectContact('{{ $contact->wa_id }}')"
                        class="p-3 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <div class="font-medium text-gray-800 dark:text-white">
                            {{ $contact->name ?? $contact->wa_id }}
                        </div>
                        <div class="text-xs text-gray-500 truncate">
                            {{ Str::limit($contact->last_message, 30) }}
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 dark:text-gray-400 p-4">
                        No contacts yet
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Main Chat Window --}}
        <div class="flex-1 flex flex-col">
            {{-- Chat Header --}}
            <div class="px-4 py-3 border-b dark:border-gray-700 bg-gray-100 dark:bg-gray-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $activeContactName ?? 'Select a contact' }}
                </h2>
            </div>

            {{-- Message Thread --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50 dark:bg-gray-900">
                @foreach ($messages as $message)
                    <div class="flex {{ $message->created_by ? 'justify-end' : 'justify-start' }}">
                        <div
                            class="max-w-xs md:max-w-md px-4 py-2 rounded-xl shadow-sm
                        {{ $message->created_by ? 'bg-green-500 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-white' }}">
                            <p class="text-sm">{{ $message->message }}</p>
                            <span class="block text-xs mt-1 opacity-70">
                                {{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Message Input --}}
            @if ($activeContactWaId)
                <form wire:submit.prevent="sendMessage"
                    class="p-3 border-t dark:border-gray-700 bg-white dark:bg-gray-800">
                    <div class="flex items-center gap-2">
                        <input type="text" wire:model.defer="newMessage" placeholder="Type a message..."
                            class="flex-1 px-4 py-2 border rounded-full bg-gray-100 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">

                        <button type="submit"
                            class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-full">
                            Send
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

</x-filament-panels::page>
