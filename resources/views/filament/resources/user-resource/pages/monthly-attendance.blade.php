<x-filament-panels::page>

    <div class="p-4">
        {{-- Render the Form --}}
        {{ $this->form }}

        {{-- Add spacing --}}
        <div class="mt-6"></div>

        {{-- Render the Table --}}
        {{ $this->table }}
    </div>

</x-filament-panels::page>
