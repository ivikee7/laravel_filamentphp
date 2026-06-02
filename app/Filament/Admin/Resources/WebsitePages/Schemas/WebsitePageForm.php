<?php

namespace App\Filament\Admin\Resources\WebsitePages\Schemas;

use App\Filament\Admin\Resources\WebsitePages\Support\SeoAnalyzer;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class WebsitePageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Page editor')
                    ->tabs([
                        Tabs\Tab::make('Content')
                            ->schema([
                                Section::make('Page details')
                                    ->schema([
                                        TextInput::make('title')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state): void {
                                                if (blank($get('slug'))) {
                                                    $set('slug', Str::slug((string) $state));
                                                }
                                            }),
                                        TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true)
                                            ->helperText('Used in the page URL, e.g. /about-us.'),
                                        Select::make('website_category_id')
                                            ->label('Category')
                                            ->relationship('category', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->default(null),
                                        TextInput::make('template')
                                            ->maxLength(255)
                                            ->placeholder('default')
                                            ->default(null),
                                    ])
                                    ->columns(2),
                                Section::make('Page sections')
                                    ->description('Build your page by adding sections. Each section has a title, style, and rich text content.')
                                    ->schema([
                                        Repeater::make('page_sections')
                                            ->label('Sections')
                                            ->helperText('Add sections and edit content using the rich text editor.')
                                            ->default([])
                                            ->reorderableWithButtons()
                                            ->collapsible()
                                            ->schema([
                                                TextInput::make('section_key')
                                                    ->label('Section key')
                                                    ->maxLength(100)
                                                    ->placeholder('hero')
                                                    ->helperText('Unique identifier for this section.'),
                                                TextInput::make('section_title')
                                                    ->label('Section title')
                                                    ->maxLength(255)
                                                    ->helperText('Optional. Displayed as section heading.'),
                                                Select::make('section_style')
                                                    ->label('Section style')
                                                    ->options([
                                                        'default' => 'Default',
                                                        'muted' => 'Muted background',
                                                        'highlight' => 'Highlight background',
                                                    ])
                                                    ->default('default'),
                                                RichEditor::make('content')
                                                    ->label('Section content')
                                                    ->required()
                                                    ->default('')
                                                    ->columnSpanFull(),
                                            ])
                                            ->columns(2)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tabs\Tab::make('Publishing')
                            ->schema([
                                Section::make('Visibility and schedule')
                                    ->schema([
                                        Select::make('status')
                                            ->options([
                                                'draft' => 'Draft',
                                                'published' => 'Published',
                                                'archived' => 'Archived',
                                            ])
                                            ->default('draft')
                                            ->required(),
                                        DateTimePicker::make('published_at')
                                            ->seconds(false)
                                            ->default(null),
                                        TextInput::make('sort_order')
                                            ->required()
                                            ->numeric()
                                            ->default(0),
                                        Toggle::make('is_home')
                                            ->label('Set as home page')
                                            ->default(false),
                                        Toggle::make('show_in_menu')
                                            ->label('Show in menu')
                                            ->default(true),
                                    ])
                                    ->columns(2),
                            ]),
                        Tabs\Tab::make('SEO')
                            ->schema([
                                Section::make('Search metadata')
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->maxLength(255)
                                            ->live()
                                            ->helperText('30-60 characters recommended.'),
                                        TextInput::make('meta_description')
                                            ->maxLength(500)
                                            ->live()
                                            ->helperText('120-160 characters recommended.')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1),
                                Section::make('Real-time SEO Analysis')
                                    ->description('Your SEO score and optimization suggestions.')
                                    ->schema([
                                        \Filament\Forms\Components\Placeholder::make('seo_guidelines')
                                            ->content(new \Illuminate\Support\HtmlString(
                                                '<div class="text-sm space-y-2"><strong>SEO Guidelines:</strong>' .
                                                '<ul class="list-disc ml-4"><li>Title: 30-60 characters for best results</li>' .
                                                '<li>Description: 120-160 characters recommended</li>' .
                                                '<li>Content: Include at least 100 words for better ranking</li>' .
                                                '<li>Use sections to organize content logically</li></ul></div>'
                                            ))
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->columns(1);
    }
}
