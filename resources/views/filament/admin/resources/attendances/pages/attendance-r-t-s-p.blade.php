<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Status/Feedback Display --}}
        <div @class([
            'p-6 rounded-lg shadow-xl text-center',
            'bg-green-100 border border-green-400 text-green-700' => $status === 'success',
            'bg-red-100 border border-red-400 text-red-700' => $status === 'danger',
            'bg-yellow-100 border border-yellow-400 text-yellow-700' => $status === 'warning',
            'bg-gray-100 border border-gray-400 text-gray-700' => $status === 'info',
        ])>
            @if ($userName)
                <h1 class="text-4xl font-extrabold mb-2">{{ $userName }}</h1>
                <p class="text-xl">Clock-In Recorded!</p>
            @else
                <h1 class="text-3xl font-bold mb-2">Facial Attendance Check</h1>
            @endif

            <p class="text-lg mt-4">{{ $message }}</p>
        </div>

        {{-- The Action Button --}}
        <div class="text-center">
            <x-filament::button
                wire:click="clockIn"
                wire:loading.attr="disabled"
                color="primary"
                size="xl"
            >
{{--                <div wire:poll.5s="clockIn">--}}
                <span wire:loading.remove wire:target="clockIn">
                    <x-heroicon-o-eye class="w-6 h-6 mr-2 inline-block"/>
                    Start Face Scan
                </span>
                <span wire:loading wire:target="clockIn" class="flex items-center">
                    <x-filament::loading-indicator class="w-6 h-6 mr-2 animate-spin" />
                    Scanning... Please Wait (Up to 20s)
                </span>
            </x-filament::button>

            <p class="text-sm text-gray-500 mt-2">
                Scanning the RTSP stream can take several seconds due to network latency and processing time.
            </p>
        </div>
    </div>
</x-filament-panels::page>
