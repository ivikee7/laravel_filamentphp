<x-filament-panels::page>
    <div class="max-w-5xl mx-auto bg-white dark:bg-gray-900 dark:text-white shadow rounded-lg border p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
            {{-- Avatar --}}
            <div class="flex justify-center">
                <div class="flex">
                    <img src="{{ $record->avatar ? asset('storage/' . $record->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($record->name) }}"
                        alt="{{ $record->name }}" class="w-32 h-32 rounded-full object-cover shadow-md">
                </div>
                {{-- <div><strong>Blood Group:</strong>{{ $record->blood_group }}</div> --}}
            </div>

            {{-- Name + Role + QR Code --}}
            <div class="text-center space-y-2">
                <h2 class="text-2xl font-bold dark:text-white">{{ $record->name }}</h2>
                <p class="dark:text-white">{{ $record->roles->pluck('name')->implode(', ') }}</p>
                <div class="flex justify-center">
                    <div class="bg-white p-2 rounded">
                        {!! QrCode::size(100)->generate(route('filament.admin.pages.id-cards.{record}', ['record' => $record->id])) !!}
                    </div>
                </div>
            </div>

            {{-- Contact Info --}}

            <div class="text-sm space-y-1 dark:text-white">
                <div><strong>ID #:</strong> {{ $record->id }}</div>
                <div><strong>Class:</strong> {{ $record->student?->currentClassAssignment?->class?->className?->name }}</div>
                <div><strong>Section:</strong> {{ $record->student?->currentClassAssignment?->section?->name }}</div>
                <div><strong>Father's Name:</strong> {{ $record->father_name }}</div>
                <div><strong>Mother's Name:</strong> {{ $record->mother_name }}</div>
                <div><strong>Primary Contact:</strong> {{ $record->primary_contact_number }}</div>
                <div><strong>Secondary Contact:</strong> {{ $record->secondary_contact_number }}</div>
                <div><strong>Address:</strong> {{ $record->address }}, {{ $record->city }}, {{ $record->state }},
                    {{ $record->pin_code }}</div>
            </div>
        </div>
    </div>
    @if (\App\Filament\Admin\Pages\IDCards\ViewIDCard::canMarkAttendance())
        <div>
            {{-- Third Row: Attendance Buttons --}}
            <div class="flex flex-wrap justify-center gap-4 p-6">
                {{-- @unless (in_array('entredInBus', $attendanceRecords))
                <x-filament::button wire:click="markAttendance('entredInBus')" color="primary">
                    Entered in Bus
                </x-filament::button>
            @endunless --}}

                @unless (in_array('entredInCampus', $attendanceRecords))
                    <x-filament::button wire:click="markAttendance('entredInCampus')" color="success">
                        Entered in Campus
                    </x-filament::button>
                @endunless

                @unless (in_array('exitFromCampus', $attendanceRecords))
                    <x-filament::button wire:click="markAttendance('exitFromCampus')" color="warning">
                        Exit from Campus
                    </x-filament::button>
                @endunless

                {{-- @unless (in_array('exitFromBus', $attendanceRecords))
                <x-filament::button wire:click="markAttendance('exitFromBus')" color="danger">
                    Exit from Bus
                </x-filament::button>
            @endunless --}}
            </div>
        </div>
    @endif
</x-filament-panels::page>
