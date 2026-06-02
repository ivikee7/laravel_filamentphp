@extends('website.layouts.layout')

@section('title', ($page?->meta_title ?? $page?->title ?? 'Page') . ' - ' . config('app.name'))
@section('meta_description', ($page?->meta_description ?? $page?->excerpt ?? 'A leading CBSE school in Patna, Bihar.'))

@section('content')
    <section class="container">
        <article class="page-card">
            @if ($page && $page->content)
                @php
                    $sections = \App\Filament\Admin\Resources\WebsitePages\Support\WebsitePageBuilder::extract($page->content);
                    if ($sections) {
                        $rendered = \App\Filament\Admin\Resources\WebsitePages\Support\WebsitePageBuilder::render($sections);
                    } else {
                        $rendered = \App\Filament\Admin\Resources\WebsitePages\Support\WebsitePageBuilder::stripMeta($page->content);
                        $rendered = str_replace(['\n', "\\n"], '', $rendered);
                        $rendered = trim($rendered, "\n");
                    }
                @endphp

                @if ($rendered)
                    {!! $rendered !!}
                @else
                    <p style="color: #64748b;">This page has no content yet.</p>
                @endif
            @else
                <h1>Page not available</h1>
                <p style="color: #64748b; margin-top: .4rem;">This page is currently unavailable or unpublished.</p>
            @endif
        </article>
    </section>
@endsection
