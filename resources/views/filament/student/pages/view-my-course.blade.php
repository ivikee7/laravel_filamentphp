<x-filament-panels::page>
@php
    $record   = $this->record;
    $lessons  = $record->lessons()->where('is_published', true)->orderBy('order')->get();
    $materials = $record->materials()->where('is_published', true)->orderBy('order')->get();
    $exams    = $record->exams()->where('status', 'published')->orderBy('exam_date')->get();
    $activeTab = request()->get('tab', 'lessons');
    $student = auth()->user()?->student;
    $submissionMap = collect();
    $examMarksById = $exams->mapWithKeys(fn ($exam) => [$exam->id => (float) $exam->total_marks]);

    if ($student && $exams->isNotEmpty()) {
        $submissionMap = \App\Models\ExamSubmission::query()
            ->where('student_id', $student->id)
            ->whereIn('exam_id', $exams->pluck('id'))
            ->get()
            ->keyBy('exam_id');
    }

    $completedExamCount = $submissionMap->whereIn('status', ['submitted', 'graded'])->count();
    $gradedExamCount = $submissionMap->where('status', 'graded')->count();
    $avgScorePct = null;

    if ($gradedExamCount > 0) {
        $avgScorePct = round($submissionMap
            ->where('status', 'graded')
            ->avg(fn ($submission) => (($examMarksById[$submission->exam_id] ?? 0) > 0)
                ? ((float) $submission->score / (float) ($examMarksById[$submission->exam_id] ?? 0)) * 100
                : 0
            ));
    }
@endphp

<div class="space-y-5" x-data="{ tab: '{{ $activeTab }}' }">

    {{-- ── Course Hero ── --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary-600 to-primary-800 p-6 text-white shadow-lg">
        <div class="absolute inset-0 opacity-10"
             style="background-image: url('data:image/svg+xml,<svg width=&quot;40&quot; height=&quot;40&quot; viewBox=&quot;0 0 40 40&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;><g fill=&quot;%23fff&quot; fill-opacity=&quot;1&quot;><circle cx=&quot;20&quot; cy=&quot;20&quot; r=&quot;2&quot;/></g></svg>')"></div>
        <div class="relative flex flex-col md:flex-row md:items-start gap-6">
            @if($record->thumbnail)
                <img src="{{ asset('storage/' . $record->thumbnail) }}"
                     alt="{{ $record->title }}"
                     class="w-28 h-28 rounded-xl object-cover flex-shrink-0 shadow-md border-2 border-white/30">
            @else
                <div class="w-28 h-28 rounded-xl flex items-center justify-center bg-white/20 flex-shrink-0 text-4xl">
                    📚
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    @if($record->code)
                        <span class="inline-flex items-center rounded-md bg-white/20 px-2.5 py-0.5 text-xs font-semibold">
                            {{ $record->code }}
                        </span>
                    @endif
                    @if($record->subject)
                        <span class="inline-flex items-center rounded-md bg-blue-400/30 px-2.5 py-0.5 text-xs font-semibold">
                            {{ $record->subject->name }}
                        </span>
                    @endif
                </div>
                <h1 class="text-2xl font-bold leading-tight">{{ $record->title }}</h1>
                @if($record->description)
                    <div class="mt-2 text-sm text-white/80 line-clamp-2">{!! strip_tags($record->description) !!}</div>
                @endif
                <div class="mt-4 flex flex-wrap gap-4 text-sm text-white/80">
                    @if($record->instructor)
                        <span class="flex items-center gap-1.5">
                            <x-heroicon-m-user class="h-4 w-4"/>
                            {{ $record->instructor->name }}
                        </span>
                    @endif
                    @if($record->academicYear)
                        <span class="flex items-center gap-1.5">
                            <x-heroicon-m-calendar class="h-4 w-4"/>
                            {{ $record->academicYear->name }}
                        </span>
                    @endif
                </div>
            </div>
            {{-- Stats badges --}}
            <div class="flex md:flex-col gap-2 md:items-end flex-shrink-0">
                <span class="inline-flex items-center gap-1 rounded-lg bg-white/20 px-3 py-1.5 text-sm font-semibold">
                    <x-heroicon-m-book-open class="h-4 w-4"/> {{ $lessons->count() }} Lessons
                </span>
                <span class="inline-flex items-center gap-1 rounded-lg bg-white/20 px-3 py-1.5 text-sm font-semibold">
                    <x-heroicon-m-paper-clip class="h-4 w-4"/> {{ $materials->count() }} Materials
                </span>
                <span class="inline-flex items-center gap-1 rounded-lg bg-white/20 px-3 py-1.5 text-sm font-semibold">
                    <x-heroicon-m-clipboard-document-list class="h-4 w-4"/> {{ $exams->count() }} Exams
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs uppercase tracking-wide text-gray-500">Learning Units</p>
            <p class="mt-1 text-xl font-bold text-gray-900 dark:text-white">{{ $lessons->count() + $materials->count() }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs uppercase tracking-wide text-gray-500">Exam Completion</p>
            <p class="mt-1 text-xl font-bold text-gray-900 dark:text-white">{{ $completedExamCount }}/{{ $exams->count() }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs uppercase tracking-wide text-gray-500">Graded Exams</p>
            <p class="mt-1 text-xl font-bold text-gray-900 dark:text-white">{{ $gradedExamCount }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs uppercase tracking-wide text-gray-500">Average Score</p>
            <p class="mt-1 text-xl font-bold text-gray-900 dark:text-white">{{ $avgScorePct !== null ? $avgScorePct . '%' : 'N/A' }}</p>
        </div>
    </div>

    {{-- ── Tab Nav ─ --}}
    <div class="flex gap-1 rounded-xl bg-gray-100 dark:bg-gray-800 p-1">
        @foreach([
            ['id' => 'lessons',   'icon' => 'heroicon-m-book-open',            'label' => 'Lessons',   'count' => $lessons->count()],
            ['id' => 'materials', 'icon' => 'heroicon-m-paper-clip',           'label' => 'Materials', 'count' => $materials->count()],
            ['id' => 'exams',     'icon' => 'heroicon-m-clipboard-document-list','label' => 'Exams',   'count' => $exams->count()],
        ] as $t)
            <button
                type="button"
                @click="tab = '{{ $t['id'] }}'"
                :class="tab === '{{ $t['id'] }}' ? 'bg-white dark:bg-gray-700 shadow text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200'"
                class="flex-1 flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm transition-all"
            >
                <x-dynamic-component :component="$t['icon']" class="h-4 w-4"/>
                {{ $t['label'] }}
                @if($t['count'] > 0)
                    <span class="rounded-full bg-gray-200 dark:bg-gray-600 px-1.5 py-0.5 text-xs font-bold leading-none">{{ $t['count'] }}</span>
                @endif
            </button>
        @endforeach
    </div>

    {{-- ── Lessons Tab ── --}}
    <div x-show="tab === 'lessons'" x-transition>
        @if($lessons->isNotEmpty())
            <div class="rounded-2xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-200 dark:ring-white/10 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                        <thead class="bg-gray-50 dark:bg-white/5">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Lesson</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Duration</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Content Preview</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                            @foreach($lessons as $i => $lesson)
                                @php
                                    $rawLessonContent = trim((string) $lesson->content);
                                    $looksLikeHtml = preg_match('/<\s*[a-z!\/][^>]*>/i', $rawLessonContent) === 1;
                                    $allowedLessonTags = '<p><br><ul><ol><li><strong><em><b><i><u><blockquote><code><pre><h1><h2><h3><h4><h5><h6><a><img><table><thead><tbody><tr><th><td><hr>';
                                    $lessonRendered = $looksLikeHtml
                                        ? strip_tags($rawLessonContent, $allowedLessonTags)
                                        : \Illuminate\Support\Str::markdown($rawLessonContent, ['html_input' => 'strip', 'allow_unsafe_links' => false]);
                                @endphp
                                <tr class="align-top">
                                    <td class="px-4 py-3 text-sm font-semibold text-primary-700 dark:text-primary-300">{{ $i + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $lesson->title }}</p>
                                        @if($lesson->description)
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $lesson->description }}</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $lesson->duration_minutes ? $lesson->duration_minutes . ' min' : '—' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($lesson->content)
                                            <div class="prose prose-sm dark:prose-invert max-w-none line-clamp-4">
                                                {!! $lessonRendered !!}
                                            </div>
                                        @else
                                            <span class="text-xs italic text-gray-400">No content</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="rounded-2xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-200 dark:ring-white/10 py-16 text-center">
                <x-heroicon-o-book-open class="mx-auto h-14 w-14 text-gray-200 dark:text-gray-700"/>
                <p class="mt-3 text-gray-500 font-medium">No lessons published yet</p>
                <p class="text-xs text-gray-400 mt-1">Check back later for new content.</p>
            </div>
        @endif
    </div>

    {{-- ── Materials Tab ── --}}
    <div x-show="tab === 'materials'" x-transition>
        @if($materials->isNotEmpty())
            <div class="rounded-2xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-200 dark:ring-white/10 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                        <thead class="bg-gray-50 dark:bg-white/5">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Title</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Description</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                            @foreach($materials as $i => $material)
                                @php
                                    $link = $material->url ?? ($material->file_path ? asset('storage/' . $material->file_path) : null);
                                @endphp
                                <tr>
                                    <td class="px-4 py-3 text-sm font-semibold text-primary-700 dark:text-primary-300">{{ $i + 1 }}</td>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">{{ $material->title }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-800 px-2 py-0.5 text-xs font-semibold text-gray-700 dark:text-gray-300 capitalize">
                                            {{ $material->type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $material->description ?: '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @if($link)
                                            <a href="{{ $link }}" target="_blank"
                                               class="inline-flex items-center gap-1 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold px-3 py-1.5 transition-colors">
                                                <x-heroicon-m-arrow-top-right-on-square class="h-3.5 w-3.5"/>
                                                {{ in_array($material->type, ['video', 'link']) ? 'Open' : 'Download' }}
                                            </a>
                                        @else
                                            <span class="text-xs italic text-gray-400">Not available</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="rounded-2xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-200 dark:ring-white/10 py-16 text-center">
                <x-heroicon-o-paper-clip class="mx-auto h-14 w-14 text-gray-200 dark:text-gray-700"/>
                <p class="mt-3 text-gray-500 font-medium">No materials published yet</p>
            </div>
        @endif
    </div>

    {{-- ── Exams Tab ── --}}
    <div x-show="tab === 'exams'" x-transition>
        @if($exams->isNotEmpty())
            <div class="rounded-2xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-200 dark:ring-white/10 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                        <thead class="bg-gray-50 dark:bg-white/5">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Exam</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Marks</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Score</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                            @foreach($exams as $exam)
                                @php
                                    $submission = $submissionMap->get($exam->id);
                                    $statusColor = match($submission?->status) {
                                        'in_progress' => 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300',
                                        'submitted' => 'bg-sky-100 text-sky-700 dark:bg-sky-900 dark:text-sky-300',
                                        'graded' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                        default => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300',
                                    };
                                    $statusLabel = match($submission?->status) {
                                        'in_progress' => 'In progress',
                                        'submitted' => 'Awaiting grade',
                                        'graded' => 'Graded',
                                        default => 'Not started',
                                    };
                                    $takeUrl = \App\Filament\Student\Resources\MyExamResource\Pages\TakeExamPage::getUrl(['record' => $exam]);
                                    $attemptUrl = \App\Filament\Student\Resources\MyExamResource\Pages\AttemptViewPage::getUrl([
                                        'record' => $exam,
                                        'attempt' => $submission?->attempt_number,
                                    ]);
                                @endphp
                                <tr>
                                    <td class="px-4 py-3">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $exam->title }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $exam->duration_minutes ? $exam->duration_minutes . ' min' : '—' }}
                                        </p>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ ucfirst((string) $exam->type) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $exam->exam_date?->format('d M Y') ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $exam->total_marks }} / Pass {{ $exam->passing_marks }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold {{ $statusColor }}">{{ $statusLabel }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold text-green-600 dark:text-green-400">
                                        {{ ($submission && $submission->status === 'graded') ? ($submission->score . ' / ' . $exam->total_marks) : '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @if(!$submission || $submission->status === 'in_progress')
                                            <a href="{{ $takeUrl }}"
                                               class="inline-flex items-center gap-1 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold px-3 py-1.5 transition-colors">
                                                <x-heroicon-m-pencil-square class="h-3.5 w-3.5"/>
                                                {{ $submission ? 'Continue' : 'Take Exam' }}
                                            </a>
                                        @elseif(in_array($submission->status, ['submitted', 'graded']))
                                            <a href="{{ $attemptUrl }}"
                                               class="inline-flex items-center gap-1 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-3 py-1.5 transition-colors">
                                                <x-heroicon-m-chart-bar class="h-3.5 w-3.5"/>
                                                View Attempt
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="rounded-2xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-200 dark:ring-white/10 py-16 text-center">
                <x-heroicon-o-clipboard-document-list class="mx-auto h-14 w-14 text-gray-200 dark:text-gray-700"/>
                <p class="mt-3 text-gray-500 font-medium">No exams for this course yet</p>
            </div>
        @endif
    </div>

</div>
</x-filament-panels::page>
