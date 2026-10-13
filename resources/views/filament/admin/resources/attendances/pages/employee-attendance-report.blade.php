<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Interactive Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Staff Tracked</p>
                    <span class="rounded-lg bg-primary-50 p-2 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400">
                        <x-filament::icon icon="heroicon-o-briefcase" class="h-5 w-5" />
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-gray-950 dark:text-white">{{ $totalEmployees }}</p>
            </div>

            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Present Today</p>
                    <span class="rounded-lg bg-success-50 p-2 text-success-600 dark:bg-success-500/10 dark:text-success-400">
                        <x-filament::icon icon="heroicon-o-check-circle" class="h-5 w-5" />
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-success-600 dark:text-success-400">{{ $presentCount }}</p>
            </div>

            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Absent Today</p>
                    <span class="rounded-lg bg-danger-50 p-2 text-danger-600 dark:bg-danger-500/10 dark:text-danger-400">
                        <x-filament::icon icon="heroicon-o-x-circle" class="h-5 w-5" />
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-danger-600 dark:text-danger-400">{{ $absentCount }}</p>
            </div>

            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Late Today</p>
                    <span class="rounded-lg bg-warning-50 p-2 text-warning-600 dark:bg-warning-500/10 dark:text-warning-400">
                        <x-filament::icon icon="heroicon-o-clock" class="h-5 w-5" />
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-warning-600 dark:text-warning-400">{{ $lateCount }}</p>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">Recent Employee Activity (Today)</h3>

            @if($recentAttendances->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400 py-4 text-center">No employee attendance records found for today.</p>
            @else
                <div class="divide-y divide-gray-200 dark:divide-gray-800">
                    @foreach($recentAttendances as $attendance)
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center space-x-3">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $attendance->user->name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Email: {{ $attendance->user->email ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-xs text-gray-400">{{ $attendance->created_at?->format('h:i A') }}</span>
                                <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-medium
                                    @if($attendance->status === 'present') bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400
                                    @elseif($attendance->status === 'absent') bg-danger-50 text-danger-700 dark:bg-danger-500/10 dark:text-danger-400
                                    @else bg-warning-50 text-warning-700 dark:bg-warning-500/10 dark:text-warning-400 @endif">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
