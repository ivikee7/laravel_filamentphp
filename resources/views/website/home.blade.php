@extends('website.layouts.layout')

@php
    $heroTitle = \App\Models\WebsiteSettings::getValueByKey('homepage_hero_title', 'Welcome to ' . config('app.name'));
    $heroSubtitle = \App\Models\WebsiteSettings::getValueByKey('homepage_hero_subtitle', 'A clean, modern school website focused on clarity and quick communication.');
@endphp

@section('title', $page?->meta_title ?? (config('app.name') . ' - Modern School Website'))
@section('meta_description', $page?->meta_description ?? 'Modern school website with strong academics, campus life, and student-focused growth.')

@section('content')
    @include('website.components.forms.enquiry-form')
@endsection
