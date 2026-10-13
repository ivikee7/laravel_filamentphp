<div class="space-y-4">
    @if($employees->isEmpty())
        <div class="text-center py-6 text-gray-500 dark:text-gray-400">
            No employees found in this category.
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                <tr>
                    <th scope="col" class="px-4 py-3">ID</th>
                    <th scope="col" class="px-4 py-3">Employee Name</th>
                    <th scope="col" class="px-4 py-3">Role</th>
                    <th scope="col" class="px-4 py-3">Status / Time</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($employees as $user)
                    @php
                        $attendance = $user->attendances->first();
                    @endphp
                    <tr class="bg-white dark:bg-gray-900">
                        <td class="px-4 py-3">
                            {{ $user->id ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                            {{ $user->name }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $user->roles->pluck('name')->implode(', ') ?: 'N/A' }}
                        </td>
                        <td class="px-4 py-3">
                            @if($attendance)
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400">
                                        Present at {{ \Carbon\Carbon::parse($attendance->created_at)->format('h:i A') }}
                                    </span>
                            @else
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium bg-danger-50 text-danger-700 dark:bg-danger-500/10 dark:text-danger-400">
                                        Absent
                                    </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
