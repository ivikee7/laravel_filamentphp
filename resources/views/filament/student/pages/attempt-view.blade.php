<x-filament-panels::page>
@php
    $submission = $this->submission;
    $exam = $this->record;
    $attempts = $this->submissions ?? collect();

    $isPending = $submission && $submission->status === 'submitted';
    $isGraded = $submission && $submission->status === 'graded';

    $passMark = (float) $exam->passing_marks;
    $totalMark = (float) $exam->total_marks;
    $score = (float) ($submission?->score ?? 0);
    $passed = $isGraded && $score >= $passMark;
    $pct = $totalMark > 0 ? round(($score / $totalMark) * 100) : 0;

    $totalQuestions = $exam->questions->count();
    $answeredCount = $submission ? $submission->answers->whereNotNull('answer')->where('answer', '!=', '')->count() : 0;
    $autoCheckedCount = $submission ? $submission->answers->whereNotNull('is_correct')->count() : 0;
    $autoCorrectCount = $submission ? $submission->answers->where('is_correct', true)->count() : 0;
    $accuracy = $autoCheckedCount > 0 ? round(($autoCorrectCount / $autoCheckedCount) * 100) : null;
@endphp

<div class="space-y-5">

    @if($attempts->count() > 1)
        <div class="rounded-xl bg-white dark:bg-gray-900 ring-1 ring-gray-200 dark:ring-white/10 p-4">
            <p class="text-xs uppercase tracking-wide text-gray-500 mb-3">Attempts</p>
            <div class="flex flex-wrap gap-2">
                @foreach($attempts as $attempt)
                    <button
                        type="button"
                        wire:click="switchAttempt({{ $attempt->attempt_number }})"
                        class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold transition
                            {{ $submission?->id === $attempt->id
                                ? 'bg-primary-600 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}"
                    >
                        Attempt {{ $attempt->attempt_number }}
                        <span class="rounded-md px-1.5 py-0.5 text-[10px]
                            {{ $attempt->status === 'graded' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ ucfirst($attempt->status) }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    <div class="rounded-2xl overflow-hidden shadow-lg">
        @if($isPending)
            <div class="bg-gradient-to-br from-amber-500 to-orange-500 p-8 text-center text-white">
                <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-white/20 mb-4">
                    <x-heroicon-m-clock class="h-12 w-12"/>
                </div>
                <h2 class="text-2xl font-bold">Attempt {{ $submission?->attempt_number }} Awaiting Grading</h2>
                <p class="mt-2 text-white/80 max-w-sm mx-auto">Your answers are submitted and waiting for instructor review.</p>
            </div>
        @elseif($isGraded)
            <div class="bg-gradient-to-br {{ $passed ? 'from-green-500 to-emerald-600' : 'from-red-500 to-rose-600' }} p-8 text-center text-white">
                <div class="mx-auto mb-4 relative w-32 h-32">
                    <svg class="w-32 h-32 -rotate-90" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="50" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="10"/>
                        <circle cx="60" cy="60" r="50" fill="none" stroke="white" stroke-width="10"
                                stroke-dasharray="{{ round(314 * $pct / 100) }} 314"
                                stroke-linecap="round"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-extrabold">{{ $pct }}%</span>
                    </div>
                </div>

                <h2 class="text-3xl font-extrabold">{{ $passed ? 'Passed' : 'Not Passed' }} · Attempt {{ $submission?->attempt_number }}</h2>
                <p class="mt-2 text-5xl font-black">
                    {{ $score }}<span class="text-2xl font-normal text-white/70"> / {{ $totalMark }}</span>
                </p>
                @if($submission?->grade)
                    <p class="mt-2 text-xl font-bold">Grade: {{ $submission->grade }}</p>
                @endif
                <p class="mt-1 text-white/70 text-sm">Pass mark: {{ $passMark }}</p>
            </div>
        @endif

        <div class="grid grid-cols-3 divide-x divide-gray-200 dark:divide-white/10 bg-white dark:bg-gray-900 ring-1 ring-gray-200 dark:ring-white/10">
            <div class="py-4 text-center">
                <p class="text-xs text-gray-400 uppercase tracking-wider">Started</p>
                <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-white">{{ $submission?->started_at?->format('d M, H:i') ?? '—' }}</p>
            </div>
            <div class="py-4 text-center">
                <p class="text-xs text-gray-400 uppercase tracking-wider">Submitted</p>
                <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-white">{{ $submission?->submitted_at?->format('d M, H:i') ?? '—' }}</p>
            </div>
            <div class="py-4 text-center">
                <p class="text-xs text-gray-400 uppercase tracking-wider">Time Taken</p>
                <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-white">{{ $submission?->time_taken_minutes ? $submission->time_taken_minutes . ' min' : '—' }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs uppercase tracking-wide text-gray-500">Questions Answered</p>
            <p class="mt-1 text-xl font-bold text-gray-900 dark:text-white">{{ $answeredCount }}/{{ $totalQuestions }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs uppercase tracking-wide text-gray-500">Auto Accuracy</p>
            <p class="mt-1 text-xl font-bold text-gray-900 dark:text-white">{{ $accuracy !== null ? $accuracy . '%' : 'Pending' }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs uppercase tracking-wide text-gray-500">Pass Threshold</p>
            <p class="mt-1 text-xl font-bold text-gray-900 dark:text-white">{{ $passMark }} / {{ $totalMark }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs uppercase tracking-wide text-gray-500">Status</p>
            <p class="mt-1 text-xl font-bold text-gray-900 dark:text-white">{{ ucfirst($submission?->status ?? 'pending') }}</p>
        </div>
    </div>

    @if($submission?->remarks)
        <div class="rounded-xl bg-blue-50 dark:bg-blue-950 border border-blue-200 dark:border-blue-800 p-5">
            <p class="text-sm font-semibold text-blue-800 dark:text-blue-200 flex items-center gap-2">
                <x-heroicon-m-chat-bubble-left-ellipsis class="h-4 w-4"/> Instructor Remarks
            </p>
            <p class="mt-2 text-sm text-blue-700 dark:text-blue-300">{{ $submission->remarks }}</p>
        </div>
    @endif

    @if($isGraded && $submission)
        <div class="rounded-2xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-200 dark:ring-white/10 overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-white/10">
                <x-heroicon-m-clipboard-document-check class="h-5 w-5 text-primary-500"/>
                <h3 class="font-semibold text-gray-900 dark:text-white">Answer Review</h3>
                @if($autoCheckedCount > 0)
                    <span class="ml-auto text-xs text-gray-400">{{ $autoCorrectCount }}/{{ $autoCheckedCount }} auto-graded correct</span>
                @endif
            </div>
            <div class="divide-y divide-gray-100 dark:divide-white/5">
                @foreach($exam->questions as $index => $question)
                    @php
                        $answer = $submission->answers->firstWhere('question_id', $question->id);
                        $studentAns = $answer?->answer;
                        $isCorrect = $answer?->is_correct;
                        $marksAwarded = $answer?->marks_awarded;
                        $borderClass = match (true) {
                            $isCorrect === true => 'border-l-4 border-l-green-500',
                            $isCorrect === false => 'border-l-4 border-l-red-500',
                            default => 'border-l-4 border-l-gray-200 dark:border-l-gray-700',
                        };
                    @endphp
                    <div class="px-6 py-4 {{ $borderClass }}">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    <span class="text-gray-400 mr-1">Q{{ $index + 1 }}.</span>
                                    {{ $question->question }}
                                </p>
                                <div class="mt-2 space-y-1 text-sm">
                                    <p class="flex items-start gap-2">
                                        <span class="text-gray-400 flex-shrink-0 w-24">Your answer:</span>
                                        <span class="{{ $isCorrect === false ? 'text-red-600 dark:text-red-400 line-through' : 'text-gray-800 dark:text-gray-200' }} font-medium">
                                            {{ $studentAns ?? '(no answer)' }}
                                        </span>
                                    </p>
                                    @if($isCorrect === false && $question->correct_answer)
                                        <p class="flex items-start gap-2">
                                            <span class="text-gray-400 flex-shrink-0 w-24">Correct:</span>
                                            <span class="text-green-700 dark:text-green-300 font-medium">{{ $question->correct_answer }}</span>
                                        </p>
                                    @endif
                                </div>

                                @if($question->explanation)
                                    <div class="mt-3 rounded-lg bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-blue-700 dark:text-blue-300">Explanation</p>
                                        <p class="mt-1 text-sm text-blue-800 dark:text-blue-200 whitespace-pre-line">{{ $question->explanation }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-shrink-0 text-right">
                                @if($isCorrect === true)
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 px-2.5 py-1 text-xs font-bold">
                                        ✓ {{ $marksAwarded }}/{{ $question->marks }}
                                    </span>
                                @elseif($isCorrect === false)
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 px-2.5 py-1 text-xs font-bold">
                                        ✗ 0/{{ $question->marks }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 px-2.5 py-1 text-xs">
                                        {{ $question->marks }} marks
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif($isPending)
        <div class="rounded-xl bg-amber-50 dark:bg-amber-950 border border-amber-200 dark:border-amber-700 p-6 text-center">
            <x-heroicon-o-clock class="mx-auto h-10 w-10 text-amber-400 mb-2"/>
            <p class="text-sm font-medium text-amber-800 dark:text-amber-200">Answer review appears after grading is complete.</p>
        </div>
    @endif

</div>
</x-filament-panels::page>
