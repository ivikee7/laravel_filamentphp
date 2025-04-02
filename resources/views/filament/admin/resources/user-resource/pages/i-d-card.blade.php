<x-filament-panels::page>
    <div>
        <div class="flex flex-col items-center space-y-4">
            <div class="bg-white p-4 shadow rounded">
                {!! QrCode::size(150)->generate(route('filament.admin.resources.users.id-card', $this->record->id)) !!}
            </div>
            <h2 class="text-lg font-bold">{{ date('d-M-Y') }}</h2>

            <!-- Attendance Buttons -->
            <div class="grid grid-cols-2 gap-4">
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
        </div>
    </div>
</x-filament-panels::page>
