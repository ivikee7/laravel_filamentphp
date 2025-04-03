<x-filament-panels::page>
    {{-- First Row: Avatar & QR Code --}}
    <div class="grid grid-cols-1 gap-6 p-6 items-center">
        <div class="flex justify-center">
            <img src="{{ $record->avatar ? asset('storage/' . $record->avatar) : '/default-avatar.png' }}"
                 class="w-32 h-32 rounded shadow-lg">
        </div>
    </div>

    {{-- Second Row: User Info in Columns --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 p-6 rounded-lg">
        <div class="p-2 rounded shadow">
            <strong class="text-gray-600">Name:</strong> <br> {{ $record->name }}
        </div>
        <div class="p-2 rounded shadow">
            <strong class="text-gray-600">Father's Name:</strong> <br> {{ $record->father_name ?? 'N/A' }}
        </div>
        <div class="p-2 rounded shadow">
            <strong class="text-gray-600">Mother's Name:</strong> <br> {{ $record->mother_name ?? 'N/A' }}
        </div>
        <div class="p-2 rounded shadow">
            <strong class="text-gray-600">Address:</strong> <br> {{ $record->address ?? 'N/A' }}
        </div>
        <div class="p-2 rounded shadow">
            <strong class="text-gray-600">Class:</strong> <br> {{ $record->class->name ?? 'N/A' }}
        </div>
        <div class="p-2 rounded shadow">
            <strong class="text-gray-600">Section:</strong> <br> {{ $record->section->name ?? 'N/A' }}
        </div>
    </div>

    {{-- Third Row: Attendance Buttons --}}
    <div class="flex flex-wrap justify-center gap-4 p-6">
        <x-filament::button wire:click="markAttendance('entredInBus')" color="success">
            Entered in Bus
        </x-filament::button>

        <x-filament::button wire:click="markAttendance('entredInCampus')" color="primary">
            Entered in Campus
        </x-filament::button>

        <x-filament::button wire:click="markAttendance('exitFromCampus')" color="danger">
            Exit from Campus
        </x-filament::button>

        <x-filament::button wire:click="markAttendance('exitFromBus')" color="warning">
            Exit from Bus
        </x-filament::button>
    </div>
</x-filament-panels::page>
