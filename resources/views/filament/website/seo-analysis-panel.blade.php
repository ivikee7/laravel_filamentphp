@php
    use App\Filament\Admin\Resources\WebsitePages\Support\SeoAnalyzer;

    $title = $getRecord()?->meta_title ?? '';
    $description = $getRecord()?->meta_description ?? '';
    $content = $getRecord()?->content ?? '';

    $analysis = SeoAnalyzer::analyze($title, $description, $content);
@endphp

<div class="space-y-4">
    <!-- Overall Score Card -->
    <div class="rounded-lg border @if($analysis['overall_score'] >= 70) border-green-300 @elseif($analysis['overall_score'] >= 50) border-yellow-300 @else border-red-300 @endif bg-white p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">SEO Score</h3>
                <p class="text-sm text-gray-600">Overall optimization status</p>
            </div>
            <div class="text-right">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full @if($analysis['overall_score'] >= 70) bg-green-100 @elseif($analysis['overall_score'] >= 50) bg-yellow-100 @else bg-red-100 @endif">
                    <span class="text-4xl font-bold @if($analysis['overall_score'] >= 70) text-green-600 @elseif($analysis['overall_score'] >= 50) text-yellow-600 @else text-red-600 @endif">
                        {{ $analysis['overall_score'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Title -->
        <div class="rounded-lg border bg-white p-4">
            <div class="flex items-start justify-between mb-2">
                <div>
                    <h4 class="font-semibold text-gray-900 text-sm">Meta Title</h4>
                </div>
                <span class="inline-badge px-2 py-1 text-xs rounded @if($analysis['title']['status'] === 'good') bg-green-100 text-green-800 @elseif($analysis['title']['status'] === 'warning') bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst($analysis['title']['status']) }}
                </span>
            </div>
            <p class="text-xs text-gray-600">{{ $analysis['title']['message'] }}</p>
            <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, ($analysis['title']['length'] / 60) * 100) }}%"></div>
            </div>
            <p class="text-xs text-gray-500 mt-1">Min: 30 | Max: 60</p>
        </div>

        <!-- Description -->
        <div class="rounded-lg border bg-white p-4">
            <div class="flex items-start justify-between mb-2">
                <div>
                    <h4 class="font-semibold text-gray-900 text-sm">Meta Description</h4>
                </div>
                <span class="inline-badge px-2 py-1 text-xs rounded @if($analysis['description']['status'] === 'good') bg-green-100 text-green-800 @elseif($analysis['description']['status'] === 'warning') bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst($analysis['description']['status']) }}
                </span>
            </div>
            <p class="text-xs text-gray-600">{{ $analysis['description']['message'] }}</p>
            <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, ($analysis['description']['length'] / 160) * 100) }}%"></div>
            </div>
            <p class="text-xs text-gray-500 mt-1">Min: 120 | Max: 160</p>
        </div>

        <!-- Content -->
        <div class="rounded-lg border bg-white p-4 md:col-span-2">
            <div class="flex items-start justify-between mb-2">
                <div>
                    <h4 class="font-semibold text-gray-900 text-sm">Content Quality</h4>
                </div>
                <span class="inline-badge px-2 py-1 text-xs rounded @if($analysis['content']['status'] === 'good') bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">
                    {{ ucfirst($analysis['content']['status']) }}
                </span>
            </div>
            <p class="text-xs text-gray-600">{{ $analysis['content']['message'] }}</p>
            <div class="mt-3 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500">Word count</p>
                    <p class="text-lg font-bold text-gray-900">{{ $analysis['content']['word_count'] }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Reading time</p>
                    <p class="text-lg font-bold text-gray-900">{{ $analysis['content']['reading_time'] }} min</p>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Recommended: 100+ words</p>
        </div>
    </div>

    <!-- Tips -->
    <div class="mt-4 rounded-lg bg-blue-50 border border-blue-200 p-4">
        <h4 class="font-semibold text-blue-900 text-sm mb-2">💡 SEO Tips</h4>
        <ul class="text-sm text-blue-800 space-y-1">
            @if($analysis['title']['status'] !== 'good')
                <li>• Adjust meta title to be 30-60 characters</li>
            @endif
            @if($analysis['description']['status'] !== 'good')
                <li>• Adjust meta description to be 120-160 characters</li>
            @endif
            @if($analysis['content']['status'] !== 'good')
                <li>• Add more content to improve SEO (aim for 100+ words)</li>
            @endif
            @if($analysis['overall_score'] >= 70)
                <li>• ✅ Your page looks great! Good SEO optimization.</li>
            @endif
        </ul>
    </div>
</div>

