<x-filament-panels::page>
    <div class="grid grid-cols-2 gap-4 p-4 border-b">
        {{-- First Row: Avatar & QR Code --}}
        <div class="flex justify-center">
            <img src="{{ $record->avatar_url ?? '/default-avatar.png' }}" class="w-32 h-32 rounded-full shadow-lg">
        </div>
        <div class="flex flex-col items-center">
            {!! QrCode::size(150)->generate(route('filament.admin.resources.users.id-card', $record->id)) !!}
            <h2 class="text-center mt-2">{{ date('d-M-Y') }}</h2>
        </div>
    </div>

    {{-- Second Row: User Info --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 border-b">
        <div>
            <strong>Name:</strong> {{ $record->name }}
        </div>
        <div>
            <strong>Father's Name:</strong> {{ $record->father_name }}
        </div>
        <div>
            <strong>Mother's Name:</strong> {{ $record->mother_name }}
        </div>
        <div>
            <strong>Address:</strong> {{ $record->address }}
        </div>
        <div>
            <strong>Class:</strong> {{ $record->class->name ?? 'N/A' }}
        </div>
        <div>
            <strong>Section:</strong> {{ $record->section->name ?? 'N/A' }}
        </div>
    </div>

    {{-- Third Row: Attendance Buttons --}}
    <div class="flex flex-wrap justify-center gap-4 p-4">
        <x-filament::button wire:click="entredInBus" class="bg-blue-500">Entered in Bus</x-filament::button>
        <x-filament::button wire:click="entredInCampus" class="bg-green-500">Entered in Campus</x-filament::button>
        <x-filament::button wire:click="exitFromCampus" class="bg-red-500">Exit from Campus</x-filament::button>
        <x-filament::button wire:click="exitFromBus" class="bg-yellow-500">Exit from Bus</x-filament::button>
    </div>
</x-filament-panels::page>
