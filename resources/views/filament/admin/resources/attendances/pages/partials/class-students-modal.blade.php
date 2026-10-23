<div class="space-y-4">
    @if($students->isEmpty())
        <div class="text-center py-6 text-gray-500 dark:text-gray-400">
            No students found in this category.
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                <tr>
                    <th scope="col" class="px-4 py-3">ID</th>
                    <th scope="col" class="px-4 py-3">Student Name</th>
                    <th scope="col" class="px-4 py-3">Father's Name</th>
                    <th scope="col" class="px-4 py-3">Status / Time</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($students as $user)
                    @php
                        $attendance = $user->attendances->first();
                    @endphp
                    <tr class="bg-white dark:bg-gray-900">
                        <td class="px-4 py-3">
                            {{ $user->student?->id ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                            <div class="inline-flex items-center gap-1.5">
                                <span>{{ $user->name }}</span>

                                <!-- View Profile Icon Link using Filament's Heroicon component -->
                                <a href="{{ App\Filament\Admin\Resources\Students\StudentResource::getUrl('view', ['record' => $user->student->id]) }}"
                                   class="text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition"
                                   title="View Profile">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            {{ $user->father_name ?? 'N/A' }}
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
