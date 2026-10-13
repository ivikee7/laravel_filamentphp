<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Interactive Top Stats Grid -->
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
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Not Logged / Late</p>
                    <span class="rounded-lg bg-warning-50 p-2 text-warning-600 dark:bg-warning-500/10 dark:text-warning-400">
                        <x-filament::icon icon="heroicon-o-clock" class="h-5 w-5" />
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-warning-600 dark:text-warning-400">0</p>
            </div>
        </div>

        <!-- Role-Wise Bifurcation Section with Progress Bars -->
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 mt-6">
            <h3 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">Role-Wise Employee Breakdown (Today)</h3>
            @if(empty($roleBreakdown) || $roleBreakdown->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400 py-4 text-center">No employee records available.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($roleBreakdown as $roleName => $stats)
                        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-900 dark:text-white capitalize text-sm">{{ $roleName }}</span>
                                <span class="text-xs font-medium px-2 py-0.5 rounded bg-primary-50 text-primary-700 dark:bg-primary-500/10 dark:text-primary-400">
                                    {{ $stats['percentage'] }}% Present
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                <div class="bg-success-500 h-2 rounded-full" style="width: {{ $stats['percentage'] }}%"></div>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 grid grid-cols-3 gap-1 pt-1 text-center">
                                <span class="bg-gray-100 dark:bg-gray-700 rounded p-1">Total: <strong>{{ $stats['total'] }}</strong></span>
                                <span class="bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400 rounded p-1">Pres: <strong>{{ $stats['present'] }}</strong></span>
                                <span class="bg-danger-50 text-danger-700 dark:bg-danger-500/10 dark:text-danger-400 rounded p-1">Abs: <strong>{{ $stats['absent'] }}</strong></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
