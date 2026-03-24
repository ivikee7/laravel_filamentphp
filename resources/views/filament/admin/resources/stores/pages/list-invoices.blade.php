<x-filament-panels::page>
    <x-filament::tabs>
        @foreach ($this->getTabs() as $key => $tab)
            <x-filament::tabs.item
                :active="$activeTab === $key"
                wire:click="$set('activeTab', '{{ $key }}')"
                :badge="$tab->getBadge()"
            >
                {{ $tab->getLabel() }}
            </x-filament::tabs.item>
        @endforeach
    </x-filament::tabs>
    {{ $this->table }}
</x-filament-panels::page>
