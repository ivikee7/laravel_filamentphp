<x-filament-panels::page>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4 justify-center">
        @foreach ($users as $user)
            <div class="h-[3.375in] w-[2.125in] bg-white dark:bg-white shadow-lg border rounded-lg flex flex-col items-center justify-between p-2 overflow-hidden">

                <!-- Avatar -->
                <div class="w-full flex justify-center mt-2">
                    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-20 h-20 rounded-full object-cover border shadow-md">
                </div>

                <!-- User Info -->
                <div class="text-center flex-1 flex flex-col justify-center text-xs leading-tight w-full px-1 space-y-1 text-black dark:!text-black">
                    <strong class="text-sm block truncate text-black dark:!text-black">{{ $user->name ?? 'N/A' }}</strong>
                    <span class="truncate text-black dark:!text-black">Father: {{ $user->father_name ?? 'N/A' }}</span>
                    <span class="truncate text-black dark:!text-black">Mother: {{ $user->mother_name ?? 'N/A' }}</span>
                    <span class="truncate text-black dark:!text-black">Class: {{ $user->class->name ?? 'N/A' }}</span>
                    <span class="truncate text-black dark:!text-black">Section: {{ $user->section->name ?? 'N/A' }}</span>
                </div>

                <!-- Footer Date -->
                <h2 class="text-xs font-bold mb-2 text-black dark:!text-black">{{ date('d-M-Y') }}</h2>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
