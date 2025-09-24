<x-filament-panels::page>
    <form wire:submit.prevent="send" class="space-y-4">
        <div>
            {{ $this->form }}
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            Send SMS
        </button>
    </form>
</x-filament-panels::page>
