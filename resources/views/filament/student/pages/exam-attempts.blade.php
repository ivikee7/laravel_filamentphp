<x-filament-panels::page>
@php
    $exam = $this->record;
    $attempts = $this->attempts;
    $averageScore = $this->averageScore;
    $bestAttempt = $this->bestAttempt;
    $latestAttempt = $this->latestAttempt;
    $gradedCount = $attempts->where('status', 'graded')->count();
@endphp

<div class="space-y-5">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs uppercase tracking-wide text-gray-500">Total Attempts</p>
            <p class="mt-1 text-xl font-bold text-gray-900 dark:text-white">{{ $attempts->count() }}</p>
        </div>

        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs uppercase tracking-wide text-gray-500">Graded Attempts</p>
            <p class="mt-1 text-xl font-bold text-gray-900 dark:text-white">{{ $gradedCount }}</p>
        </div>

        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs uppercase tracking-wide text-gray-500">Average Score</p>
            <p class="mt-1 text-xl font-bold text-gray-900 dark:text-white">
                {{ $averageScore !== null ? $averageScore . ' / ' . $exam->total_marks : '—' }}
            </p>
        </div>

        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs uppercase tracking-wide text-gray-500">Best Score</p>
            <p class="mt-1 text-xl font-bold text-gray-900 dark:text-white">
                {{ $bestAttempt?->score !== null ? $bestAttempt->score . ' (A' . $bestAttempt->attempt_number . ')' : '—' }}
            </p>
        </div>
    </div>

    <div class="rounded-2xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-200 dark:ring-white/10 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-white/10">
            <h3 class="font-semibold text-gray-900 dark:text-white">Attempts Table</h3>
            <p class="text-xs text-gray-500 mt-1">Latest attempt: A{{ $latestAttempt?->attempt_number ?? '—' }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr class="text-left text-xs uppercase tracking-wide text-gray-500">
                        <th class="px-4 py-3">Attempt</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Started</th>
                        <th class="px-4 py-3">Submitted</th>
                        <th class="px-4 py-3">Time</th>
                        <th class="px-4 py-3">Score</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @foreach($attempts as $attempt)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">A{{ $attempt->attempt_number }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold
                                    {{ $attempt->status === 'graded' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : ($attempt->status === 'submitted' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300') }}">
                                    {{ ucfirst($attempt->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $attempt->started_at?->format('d M Y, H:i') ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $attempt->submitted_at?->format('d M Y, H:i') ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $attempt->time_taken_minutes ? $attempt->time_taken_minutes . ' min' : '—' }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">
                                {{ $attempt->score !== null ? $attempt->score . ' / ' . $exam->total_marks : 'Pending' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a
                                    href="{{ \App\Filament\Student\Resources\MyExamResource\Pages\AttemptViewPage::getUrl(['record' => $exam, 'attempt' => $attempt->attempt_number]) }}"
                                    class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-3 py-2 text-xs font-semibold text-white hover:bg-primary-700"
                                >
                                    View Attempt
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-filament-panels::page>
