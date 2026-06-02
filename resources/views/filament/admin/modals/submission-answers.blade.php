<div class="space-y-3 py-2">
    @forelse($submission->answers->sortBy(fn($a) => $a->question?->order) as $answer)
        @php
            $question = $answer->question;
            $correct  = $answer->is_correct;
        @endphp
        <div class="rounded-lg border p-3
            {{ $correct === true  ? 'border-green-300 bg-green-50 dark:bg-green-950 dark:border-green-700' :
               ($correct === false ? 'border-red-300 bg-red-50 dark:bg-red-950 dark:border-red-700' :
                                     'border-gray-200 bg-gray-50 dark:bg-gray-800 dark:border-gray-700') }}">
            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                Q{{ $question?->order ?? '?' }}. {{ $question?->question ?? 'Question deleted' }}
                <span class="ml-2 text-xs font-normal text-gray-500">({{ $question?->marks ?? 0 }} marks)</span>
            </p>
            <div class="mt-1.5 text-sm space-y-0.5">
                <p><span class="font-medium text-gray-500">Answer:</span>
                    <span class="{{ $correct === false ? 'text-red-600 line-through' : 'text-gray-800 dark:text-gray-200' }}">
                        {{ $answer->answer ?? '(no answer)' }}
                    </span>
                </p>
                @if($correct === false && $question?->correct_answer)
                    <p><span class="font-medium text-green-600">Correct:</span>
                        <span class="text-green-700 dark:text-green-300">{{ $question->correct_answer }}</span>
                    </p>
                @endif
                @if($answer->marks_awarded !== null)
                    <p class="text-xs text-gray-500">Marks: {{ $answer->marks_awarded }} / {{ $question?->marks }}</p>
                @endif
            </div>
        </div>
    @empty
        <p class="text-center text-sm text-gray-400 py-4">No answers recorded.</p>
    @endforelse
</div>

