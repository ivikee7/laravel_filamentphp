<x-filament-panels::page>
    <div class="max-w-5xl mx-auto bg-white dark:bg-gray-900 dark:text-white shadow rounded-lg border p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
            {{-- Avatar --}}
            <div class="flex justify-center">
                <img src="{{ $record->avatar ? asset('storage/' . $record->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($record->name) }}"
                    alt="{{ $record->name }}" class="w-32 h-32 rounded-full object-cover shadow-md">
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
            @php
                $student = $record->student;
                $assignment = $student?->classAssignments()->latest()->first();
            @endphp

            <div class="text-sm space-y-1 dark:text-white">
                <div><strong>ID #:</strong> {{ $record->id ?? 'N/A' }}</div>
                <div><strong>Class:</strong> {{ $student?->currentClassAssignment?->class?->name ?? 'N/A' }}</div>
                <div><strong>Section:</strong> {{ $student?->currentClassAssignment?->section?->name ?? 'N/A' }}</div>
                <div><strong>Father's Name:</strong> {{ $record->father_name ?? 'N/A' }}</div>
                <div><strong>Mother's Name:</strong> {{ $record->mother_name ?? 'N/A' }}</div>
                <div><strong>Primary Contact:</strong> {{ $record->primary_contact_number ?? 'N/A' }}</div>
                <div><strong>Secondary Contact:</strong> {{ $record->secondary_contact_number ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div>
        {{-- Third Row: Attendance Buttons --}}
        <div class="flex flex-wrap justify-center gap-4 p-6">
            @unless (in_array('entredInBus', $attendanceRecords))
                <x-filament::button wire:click="markAttendance('entredInBus')" color="success">
                    Entered in Bus
                </x-filament::button>
            @endunless

            @unless (in_array('entredInCampus', $attendanceRecords))
                <x-filament::button wire:click="markAttendance('entredInCampus')" color="primary">
                    Entered in Campus
                </x-filament::button>
            @endunless

            @unless (in_array('exitFromCampus', $attendanceRecords))
                <x-filament::button wire:click="markAttendance('exitFromCampus')" color="danger">
                    Exit from Campus
                </x-filament::button>
            @endunless

            @unless (in_array('exitFromBus', $attendanceRecords))
                <x-filament::button wire:click="markAttendance('exitFromBus')" color="warning">
                    Exit from Bus
                </x-filament::button>
            @endunless
        </div>
    </div>
</x-filament-panels::page>
