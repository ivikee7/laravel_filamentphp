<x-filament-panels::page>
@php
    $exam        = $this->record;
    $questions   = $exam->questions;
    $submission  = $this->submission;
    $answered    = collect($this->answers)->filter(fn($v) => $v !== null && $v !== '')->count();
    $total       = $questions->count();
    $answeredMap = collect($this->answers)
        ->map(fn ($value) => $value !== null && $value !== '')
        ->all();
    $attemptCount    = $this->attemptCount;
    $maxAttempts     = $exam->max_attempts;
    $attemptsLabel   = $maxAttempts === null ? 'Unlimited' : $maxAttempts;
    $attemptsExhausted = $this->attemptsExhausted;
@endphp
{{-- ── Attempts Exhausted Gate ── --}}
@if($attemptsExhausted)
<div class="flex flex-col items-center justify-center gap-6 py-20">
    <div class="rounded-full bg-red-100 dark:bg-red-900/30 p-6">
        <x-heroicon-o-lock-closed class="w-16 h-16 text-red-500" />
    </div>
    <div class="text-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">No More Attempts</h2>
        <p class="mt-2 text-gray-500 dark:text-gray-400">
            You have used all <strong>{{ $attemptsLabel }}</strong> allowed attempt(s) for this exam.
        </p>
        <p class="mt-1 text-sm text-gray-400">Contact your instructor if you need an exception.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ \App\Filament\Student\Resources\MyExamResource\Pages\AttemptViewPage::getUrl(['record' => $exam]) }}"
           class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-primary-700">
            View Last Attempt
        </a>
        <a href="{{ \App\Filament\Student\Resources\MyExamResource::getUrl('index') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-gray-100 dark:bg-gray-800 px-5 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 shadow hover:bg-gray-200">
            Back to Exams
        </a>
    </div>
</div>
@else

<div class="space-y-4"
     x-data="{
         currentQ: 1,
         totalQ: {{ $total }},
         answered: {{ $answered }},
         answeredMap: @js($answeredMap),
         completionPercent() {
             if (!this.totalQ) return 0;
             return Math.round((this.answered / this.totalQ) * 100);
         },
         updateAnswered(questionId, hasValue) {
             this.answeredMap[questionId] = hasValue;
             this.answered = Object.values(this.answeredMap).filter(Boolean).length;
         },
         @if($exam->duration_minutes && $submission)
         timeLeft: {{ max(0, ($exam->duration_minutes * 60) - now()->diffInSeconds($submission->started_at)) }},
         timerActive: true,
         get formattedTime() {
             const m = String(Math.floor(this.timeLeft / 60)).padStart(2, '0');
             const s = String(this.timeLeft % 60).padStart(2, '0');
             return m + ':' + s;
         },
         get timerColor() {
             if (this.timeLeft <= 60) return 'text-red-600';
             if (this.timeLeft <= 300) return 'text-amber-500';
             return 'text-green-600';
         }
         @else
         timerActive: false,
         @endif
     }"
     @if($exam->duration_minutes && $submission)
     x-init="
         $watch('timerActive', v => {});
         setInterval(() => {
             if (!timerActive || timeLeft <= 0) return;
             timeLeft--;
             if (timeLeft <= 0) { timerActive = false; $wire.submitExam(); }
         }, 1000);
     "
     @endif
>

    {{-- ── Sticky Header Bar ── --}}
    <div class="sticky top-0 z-30 rounded-xl bg-white dark:bg-gray-900 shadow-md ring-1 ring-gray-200 dark:ring-white/10 px-5 py-3 flex flex-wrap items-center gap-4">
        {{-- Title --}}
        <div class="flex-1 min-w-0">
            <p class="font-bold text-gray-900 dark:text-white truncate">{{ $exam->title }}</p>
            <p class="text-xs text-gray-500">{{ $exam->total_marks }} marks · Pass: {{ $exam->passing_marks }}</p>
        </div>

        {{-- Progress --}}
        <div class="flex items-center gap-3 text-sm">
            <span class="text-gray-500">Progress:</span>
            <span class="font-bold text-primary-600">
                <span x-text="answered"></span> / {{ $total }}
            </span>
            <div class="w-24 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                <div class="h-full bg-primary-500 rounded-full transition-all duration-300"
                     :style="`width: ${completionPercent()}%`"></div>
            </div>
        </div>

        {{-- Timer --}}
        @if($exam->duration_minutes)
            <div class="flex items-center gap-2 text-sm font-mono font-bold" :class="timerColor">
                <x-heroicon-m-clock class="h-4 w-4"/>
                <span x-text="timerActive ? formattedTime : '--:--'"></span>
            </div>
        @endif

        <span class="inline-flex items-center gap-1 rounded-md bg-primary-50 px-2.5 py-1 text-xs font-semibold text-primary-700 dark:bg-primary-900/30 dark:text-primary-300">
            <x-heroicon-m-sparkles class="h-3.5 w-3.5"/> Focus Mode
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">

        {{-- ── Question Navigator Sidebar ── --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24 rounded-xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-200 dark:ring-white/10 p-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Questions</p>
                <div class="grid grid-cols-5 gap-1.5">
                    @foreach($questions as $i => $q)
                        <button
                            type="button"
                            @click="currentQ = {{ $i + 1 }}; document.getElementById('q-{{ $q->id }}').scrollIntoView({behavior:'smooth', block:'center'})"
                            :class="currentQ === {{ $i + 1 }}
                                ? 'ring-2 ring-primary-500 bg-primary-50 dark:bg-primary-900 text-primary-700 dark:text-primary-300 font-bold'
                                : ({{ json_encode(isset($this->answers[$q->id]) && $this->answers[$q->id] !== null && $this->answers[$q->id] !== '') }}
                                    ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300'
                                    : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200')"
                            class="w-full aspect-square rounded-lg text-xs flex items-center justify-center transition-all"
                        >{{ $i + 1 }}</button>
                    @endforeach
                </div>
                <div class="mt-4 space-y-1 text-xs text-gray-500">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded bg-green-200 dark:bg-green-800 inline-block"></span> Answered
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded bg-gray-200 dark:bg-gray-700 inline-block"></span> Not answered
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded ring-2 ring-primary-500 bg-primary-50 dark:bg-primary-900 inline-block"></span> Current
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Questions Area ── --}}
        <div class="lg:col-span-3 space-y-4">

            @if($exam->instructions)
                <div class="rounded-xl bg-amber-50 dark:bg-amber-950 border border-amber-200 dark:border-amber-800 p-4">
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-200 flex items-center gap-2">
                        <x-heroicon-m-information-circle class="h-4 w-4"/> Instructions
                    </p>
                    <p class="mt-1.5 text-sm text-amber-700 dark:text-amber-300 whitespace-pre-line">{{ $exam->instructions }}</p>
                </div>
            @endif

            @forelse($questions as $index => $question)
                <div id="q-{{ $question->id }}"
                     x-intersect="currentQ = {{ $index + 1 }}"
                     class="rounded-xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-200 dark:ring-white/10 p-5 transition-all"
                     :class="currentQ === {{ $index + 1 }} ? 'ring-2 ring-primary-400' : ''">

                    <div class="flex items-start gap-3">
                        <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300 text-sm font-bold">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 dark:text-white">
                                {{ $question->question }}
                                <span class="ml-2 text-xs font-normal text-gray-400">({{ $question->marks }} mark{{ $question->marks != 1 ? 's' : '' }})</span>
                            </p>

                            {{-- Multiple Choice --}}
                            @if($question->type === 'multiple_choice' && is_array($question->display_options))
                                <div class="mt-3 space-y-2">
                                    @php $displayOptions = $question->display_options; @endphp
                            @foreach($displayOptions as $key => $optionText)
                                        @php $selected = ($this->answers[$question->id] ?? null) == $key; @endphp
                                        <label class="flex items-center gap-3 rounded-xl border-2 p-3 cursor-pointer transition-all
                                            {{ $selected ? 'border-primary-500 bg-primary-50 dark:bg-primary-950' : 'border-gray-200 dark:border-gray-700 hover:border-primary-300 hover:bg-gray-50 dark:hover:bg-white/5' }}">
                                            <input type="radio" name="q_{{ $question->id }}" value="{{ $key }}"
                                                   {{ $selected ? 'checked' : '' }}
                                                   @click="updateAnswered({{ $question->id }}, true)"
                                                   wire:click="saveAnswer({{ $question->id }}, '{{ $key }}')"
                                                   class="text-primary-600 w-4 h-4 flex-shrink-0"/>
                                            <span class="text-sm text-gray-800 dark:text-gray-200">
                                                <strong class="text-primary-600">{{ strtoupper($key) }}.</strong> {{ $optionText }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>

                            {{-- True/False --}}
                            @elseif($question->type === 'true_false')
                                <div class="mt-3 flex gap-3">
                                    @php $displayOptions = $question->display_options; @endphp
                                    @foreach($displayOptions as $value => $label)
                                        @php $selected = ($this->answers[$question->id] ?? null) == $value; @endphp
                                        <label class="flex-1 flex items-center justify-center gap-2 rounded-xl border-2 px-4 py-3 cursor-pointer transition-all
                                            {{ $selected ? 'border-primary-500 bg-primary-50 dark:bg-primary-950' : 'border-gray-200 dark:border-gray-700 hover:border-primary-300 hover:bg-gray-50' }}">
                                            <input type="radio" name="q_{{ $question->id }}" value="{{ $value }}"
                                                   {{ $selected ? 'checked' : '' }}
                                                   @click="updateAnswered({{ $question->id }}, true)"
                                                   wire:click="saveAnswer({{ $question->id }}, '{{ $value }}')"
                                                   class="text-primary-600 w-4 h-4"/>
                                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            {{-- Short Answer --}}
                            @elseif($question->type === 'short_answer')
                                <div class="mt-3">
                                    <input type="text"
                                           placeholder="Type your answer…"
                                           value="{{ $this->answers[$question->id] ?? '' }}"
                                           @input="updateAnswered({{ $question->id }}, $event.target.value.trim().length > 0)"
                                           wire:change="saveAnswer({{ $question->id }}, $event.target.value)"
                                           class="w-full rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:border-primary-500 focus:outline-none focus:ring-0 transition-colors"/>
                                </div>

                            {{-- Essay --}}
                            @elseif($question->type === 'essay')
                                <div class="mt-3">
                                    <textarea rows="5"
                                              placeholder="Write your detailed answer here…"
                                              @input="updateAnswered({{ $question->id }}, $event.target.value.trim().length > 0)"
                                              wire:change="saveAnswer({{ $question->id }}, $event.target.value)"
                                              class="w-full rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:border-primary-500 focus:outline-none focus:ring-0 resize-y transition-colors"
                                    >{{ $this->answers[$question->id] ?? '' }}</textarea>
                                </div>
                            @endif

                            {{-- Saved indicator --}}
                            @if(isset($this->answers[$question->id]) && $this->answers[$question->id] !== null && $this->answers[$question->id] !== '')
                                <p class="mt-2 text-xs text-green-600 dark:text-green-400 flex items-center gap-1">
                                    <x-heroicon-m-check-circle class="h-3.5 w-3.5"/> Answer saved
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-200 dark:ring-white/10 py-16 text-center">
                    <x-heroicon-o-clipboard-document-list class="mx-auto h-14 w-14 text-gray-200"/>
                    <p class="mt-3 text-gray-500">No questions added to this exam yet.</p>
                </div>
            @endforelse

            {{-- ── Submit Footer ── --}}
            @if($total > 0)
                <div class="rounded-xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-200 dark:ring-white/10 p-5">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                <span x-text="answered"></span> of {{ $total }} questions answered
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                @if($answered < $total)
                                    {{ $total - $answered }} question(s) still unanswered — you can still submit.
                                @else
                                    All questions answered! Ready to submit.
                                @endif
                            </p>
                            {{-- Progress bar --}}
                            <div class="mt-2 w-48 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-300"
                                     :class="answered >= {{ $total }} ? 'bg-green-500' : 'bg-primary-500'"
                                     :style="`width: ${completionPercent()}%`"></div>
                            </div>
                        </div>
                        <button
                            wire:click="submitExam"
                            wire:confirm="Are you sure you want to submit? You cannot change answers after submission."
                            wire:loading.attr="disabled"
                            class="flex-shrink-0 inline-flex items-center gap-2 rounded-xl bg-primary-600 hover:bg-primary-700 disabled:opacity-50 text-white font-bold px-6 py-3 text-sm shadow-md transition-all"
                        >
                            <x-heroicon-m-paper-airplane class="h-4 w-4"/>
                            <span wire:loading.remove wire:target="submitExam">Submit Exam</span>
                            <span wire:loading wire:target="submitExam">Submitting…</span>
                        </button>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
</x-filament-panels::page>
@endif
