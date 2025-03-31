<x-filament-panels::page>
    <form wire:submit.prevent="updateAttendanceData">
        <label for="year">Year:</label>
        <input type="number" wire:model.defer="year" id="year">

        <label for="month">Month:</label>
        <input type="number" wire:model.defer="month" id="month">

        <button type="submit">Regenerate Table</button>
    </form>
    {{ $this->table }}
</x-filament-panels::page>
