<?php

namespace App\Filament\Admin\Pages\Website;

use App\Models\WebsiteSettings;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class WebsiteSetting extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string | \UnitEnum | null $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $slug = 'website/settings';

    protected static ?int $navigationSort = 1;

    public string $site_title = '';
    public string $site_tagline = '';
    public string $site_description = '';
    public string $site_email = '';
    public string $site_phone = '';
    public string $site_address = '';
    public string $facebook_url = '';
    public string $instagram_url = '';
    public string $youtube_url = '';
    public string $linkedin_url = '';
    public string $homepage_hero_title = '';
    public string $homepage_hero_subtitle = '';
    public string $homepage_cta_text = '';
    public string $homepage_cta_url = '';
    public string $seo_meta_title = '';
    public string $seo_meta_description = '';
    public string $seo_meta_keywords = '';
    public string $seo_meta_robots = 'index,follow';
    public string $seo_og_type = 'website';
    public string $seo_og_image = '';
    public string $seo_twitter_card = 'summary_large_image';
    public string $seo_twitter_site = '';
    public string $seo_twitter_creator = '';
    public string $seo_twitter_image = '';
    public string $seo_google_verification = '';
    public string $seo_bing_verification = '';
    public string $seo_pinterest_verification = '';
    public string $seo_theme_color = '#0b1020';
    public string $site_favicon_url = '';
    public string $site_apple_touch_icon_url = '';
    public string $seo_organization_schema = '';
    public bool $layout_show_admin_login = true;
    public string $layout_admin_login_label = 'Admin Login';
    public string $layout_admin_login_url = '/login';
    public bool $layout_show_header_showcases = true;
    public bool $layout_show_home_showcases = true;
    public bool $layout_show_sidebar_showcases = true;
    public bool $layout_show_footer_showcases = true;

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $layout_header_menu_links = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $layout_showcases = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $layout_modules = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $layout_sections = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $layout_builder = [];

    public function mount(): void
    {
        $this->site_title = (string) $this->getSetting('site_title', '');
        $this->site_tagline = (string) $this->getSetting('site_tagline', '');
        $this->site_description = (string) $this->getSetting('site_description', '');
        $this->site_email = (string) $this->getSetting('site_email', '');
        $this->site_phone = (string) $this->getSetting('site_phone', '');
        $this->site_address = (string) $this->getSetting('site_address', '');
        $this->facebook_url = (string) $this->getSetting('facebook_url', '');
        $this->instagram_url = (string) $this->getSetting('instagram_url', '');
        $this->youtube_url = (string) $this->getSetting('youtube_url', '');
        $this->linkedin_url = (string) $this->getSetting('linkedin_url', '');
        $this->homepage_hero_title = (string) $this->getSetting('homepage_hero_title', '');
        $this->homepage_hero_subtitle = (string) $this->getSetting('homepage_hero_subtitle', '');
        $this->homepage_cta_text = (string) $this->getSetting('homepage_cta_text', '');
        $this->homepage_cta_url = (string) $this->getSetting('homepage_cta_url', '');
        $this->seo_meta_title = (string) $this->getSetting('seo_meta_title', '');
        $this->seo_meta_description = (string) $this->getSetting('seo_meta_description', '');
        $this->seo_meta_keywords = (string) $this->getSetting('seo_meta_keywords', '');
        $this->seo_meta_robots = (string) $this->getSetting('seo_meta_robots', 'index,follow');
        $this->seo_og_type = (string) $this->getSetting('seo_og_type', 'website');
        $this->seo_og_image = (string) $this->getSetting('seo_og_image', '');
        $this->seo_twitter_card = (string) $this->getSetting('seo_twitter_card', 'summary_large_image');
        $this->seo_twitter_site = (string) $this->getSetting('seo_twitter_site', '');
        $this->seo_twitter_creator = (string) $this->getSetting('seo_twitter_creator', '');
        $this->seo_twitter_image = (string) $this->getSetting('seo_twitter_image', '');
        $this->seo_google_verification = (string) $this->getSetting('seo_google_verification', '');
        $this->seo_bing_verification = (string) $this->getSetting('seo_bing_verification', '');
        $this->seo_pinterest_verification = (string) $this->getSetting('seo_pinterest_verification', '');
        $this->seo_theme_color = (string) $this->getSetting('seo_theme_color', '#0b1020');
        $this->site_favicon_url = (string) $this->getSetting('site_favicon_url', '');
        $this->site_apple_touch_icon_url = (string) $this->getSetting('site_apple_touch_icon_url', '');
        $this->seo_organization_schema = (string) $this->getSetting('seo_organization_schema', '');

        $this->layout_show_admin_login = filter_var($this->getSetting('layout_show_admin_login', true), FILTER_VALIDATE_BOOLEAN);
        $this->layout_admin_login_label = (string) $this->getSetting('layout_admin_login_label', 'Admin Login');
        $this->layout_admin_login_url = (string) $this->getSetting('layout_admin_login_url', '/login');
        $this->layout_show_header_showcases = filter_var($this->getSetting('layout_show_header_showcases', true), FILTER_VALIDATE_BOOLEAN);
        $this->layout_show_home_showcases = filter_var($this->getSetting('layout_show_home_showcases', true), FILTER_VALIDATE_BOOLEAN);
        $this->layout_show_sidebar_showcases = filter_var($this->getSetting('layout_show_sidebar_showcases', true), FILTER_VALIDATE_BOOLEAN);
        $this->layout_show_footer_showcases = filter_var($this->getSetting('layout_show_footer_showcases', true), FILTER_VALIDATE_BOOLEAN);
        $this->layout_header_menu_links = $this->getJsonSetting('layout_header_menu_links', []);
        $this->layout_showcases = $this->getJsonSetting('layout_showcases', []);
        $this->layout_modules = $this->getJsonSetting('layout_modules', []);
        $this->layout_sections = $this->getJsonSetting('layout_sections', []);
        $this->layout_builder = $this->getJsonSetting('layout_builder', []);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Website Settings')
                ->description('Configure all website-level content and metadata from one place.')
                ->schema([
                    Tabs::make('Website configuration')
                        ->tabs([
                            Tabs\Tab::make('General')
                                ->schema([
                                    \Filament\Forms\Components\TextInput::make('site_title')
                                        ->label('Site Title')
                                        ->required()
                                        ->maxLength(255),
                                    \Filament\Forms\Components\TextInput::make('site_tagline')
                                        ->label('Tagline')
                                        ->maxLength(255),
                                    \Filament\Forms\Components\Textarea::make('site_description')
                                        ->label('Site Description')
                                        ->rows(4),
                                ]),
                            Tabs\Tab::make('Contact')
                                ->schema([
                                    \Filament\Forms\Components\TextInput::make('site_email')
                                        ->label('Email')
                                        ->email()
                                        ->maxLength(255),
                                    \Filament\Forms\Components\TextInput::make('site_phone')
                                        ->label('Phone')
                                        ->maxLength(50),
                                    \Filament\Forms\Components\Textarea::make('site_address')
                                        ->label('Address')
                                        ->rows(3),
                                ]),
                            Tabs\Tab::make('Social')
                                ->schema([
                                    \Filament\Forms\Components\TextInput::make('facebook_url')
                                        ->label('Facebook URL')
                                        ->url()
                                        ->maxLength(255),
                                    \Filament\Forms\Components\TextInput::make('instagram_url')
                                        ->label('Instagram URL')
                                        ->url()
                                        ->maxLength(255),
                                    \Filament\Forms\Components\TextInput::make('youtube_url')
                                        ->label('YouTube URL')
                                        ->url()
                                        ->maxLength(255),
                                    \Filament\Forms\Components\TextInput::make('linkedin_url')
                                        ->label('LinkedIn URL')
                                        ->url()
                                        ->maxLength(255),
                                ]),
                            Tabs\Tab::make('Homepage')
                                ->schema([
                                    \Filament\Forms\Components\TextInput::make('homepage_hero_title')
                                        ->label('Hero Title')
                                        ->maxLength(255),
                                    \Filament\Forms\Components\Textarea::make('homepage_hero_subtitle')
                                        ->label('Hero Subtitle')
                                        ->rows(3),
                                    \Filament\Forms\Components\TextInput::make('homepage_cta_text')
                                        ->label('CTA Text')
                                        ->maxLength(255),
                                    \Filament\Forms\Components\TextInput::make('homepage_cta_url')
                                        ->label('CTA URL')
                                        ->maxLength(255),
                                ]),
                            Tabs\Tab::make('Layout')
                                ->schema([
                                    \Filament\Schemas\Components\Section::make('JD/Astro Style Layout Builder')
                                        ->description('Build layout with sections, rows, columns, and modules. This builder can drive full page structure dynamically.')
                                        ->schema([
                                            \Filament\Forms\Components\Repeater::make('layout_builder')
                                                ->schema([
                                                    \Filament\Forms\Components\TextInput::make('section_key')
                                                        ->label('Section Key')
                                                        ->required()
                                                        ->maxLength(100),
                                                    \Filament\Forms\Components\TextInput::make('section_title')
                                                        ->label('Section Title')
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\Textarea::make('section_subtitle')
                                                        ->label('Section Subtitle')
                                                        ->rows(2),
                                                    \Filament\Forms\Components\Select::make('placement')
                                                        ->required()
                                                        ->options([
                                                            'header_before' => 'Header: Before',
                                                            'header_after' => 'Header: After',
                                                            'main_before' => 'Main: Before Content',
                                                            'main_after' => 'Main: After Content',
                                                            'sidebar' => 'Sidebar',
                                                            'footer_before' => 'Footer: Before',
                                                            'footer_after' => 'Footer: After',
                                                        ])
                                                        ->default('main_after'),
                                                    \Filament\Forms\Components\Select::make('container')
                                                        ->options([
                                                            'fixed' => 'Fixed Container',
                                                            'fluid' => 'Full Width (Fluid)',
                                                        ])
                                                        ->default('fixed'),
                                                    \Filament\Forms\Components\Select::make('background_type')
                                                        ->options([
                                                            'none' => 'None',
                                                            'color' => 'Color',
                                                            'gradient' => 'Gradient',
                                                            'image' => 'Image URL',
                                                        ])
                                                        ->default('none'),
                                                    \Filament\Forms\Components\TextInput::make('background_value')
                                                        ->label('Background Value')
                                                        ->maxLength(500)
                                                        ->helperText('Color, gradient CSS, or image URL based on background type.'),
                                                    \Filament\Forms\Components\TextInput::make('section_class')
                                                        ->label('Section Class')
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\TextInput::make('padding_top')
                                                        ->label('Padding Top (px)')
                                                        ->numeric()
                                                        ->default(24),
                                                    \Filament\Forms\Components\TextInput::make('padding_bottom')
                                                        ->label('Padding Bottom (px)')
                                                        ->numeric()
                                                        ->default(24),
                                                    \Filament\Forms\Components\Toggle::make('is_active')
                                                        ->default(true),
                                                    \Filament\Forms\Components\TextInput::make('sort_order')
                                                        ->numeric()
                                                        ->default(0),
                                                    \Filament\Forms\Components\Repeater::make('rows')
                                                        ->schema([
                                                            \Filament\Forms\Components\TextInput::make('row_key')
                                                                ->label('Row Key')
                                                                ->maxLength(100),
                                                            \Filament\Forms\Components\TextInput::make('row_class')
                                                                ->label('Row Class')
                                                                ->maxLength(255),
                                                            \Filament\Forms\Components\Repeater::make('columns')
                                                                ->schema([
                                                                    \Filament\Forms\Components\Select::make('width')
                                                                        ->label('Column Width (12-grid)')
                                                                        ->options([
                                                                            '12' => '12/12',
                                                                            '9' => '9/12',
                                                                            '8' => '8/12',
                                                                            '6' => '6/12',
                                                                            '4' => '4/12',
                                                                            '3' => '3/12',
                                                                        ])
                                                                        ->default('6')
                                                                        ->required(),
                                                                    \Filament\Forms\Components\TextInput::make('column_class')
                                                                        ->label('Column Class')
                                                                        ->maxLength(255),
                                                                    \Filament\Forms\Components\Repeater::make('modules')
                                                                        ->schema([
                                                                            \Filament\Forms\Components\TextInput::make('module_id')
                                                                                ->label('Module ID')
                                                                                ->maxLength(100),
                                                                            \Filament\Forms\Components\Select::make('module_type')
                                                                                ->required()
                                                                                ->options([
                                                                                    'heading' => 'Heading',
                                                                                    'text' => 'Text',
                                                                                    'buttons' => 'Buttons',
                                                                                    'feature_card' => 'Feature Card',
                                                                                    'list' => 'List',
                                                                                    'html' => 'Custom HTML',
                                                                                    'spacer' => 'Spacer',
                                                                                ])
                                                                                ->default('text'),
                                                                            \Filament\Forms\Components\TextInput::make('title')
                                                                                ->maxLength(255),
                                                                            \Filament\Forms\Components\Textarea::make('content')
                                                                                ->rows(3),
                                                                            \Filament\Forms\Components\TextInput::make('badge')
                                                                                ->maxLength(255),
                                                                            \Filament\Forms\Components\TextInput::make('cta_label')
                                                                                ->maxLength(255),
                                                                            \Filament\Forms\Components\TextInput::make('cta_href')
                                                                                ->maxLength(255),
                                                                            \Filament\Forms\Components\TextInput::make('secondary_label')
                                                                                ->maxLength(255),
                                                                            \Filament\Forms\Components\TextInput::make('secondary_href')
                                                                                ->maxLength(255),
                                                                            \Filament\Forms\Components\Select::make('accent')
                                                                                ->options([
                                                                                    'blue' => 'Blue',
                                                                                    'emerald' => 'Emerald',
                                                                                    'purple' => 'Purple',
                                                                                    'orange' => 'Orange',
                                                                                    'gray' => 'Gray',
                                                                                ])
                                                                                ->default('blue'),
                                                                            \Filament\Forms\Components\TextInput::make('height')
                                                                                ->label('Spacer Height (px)')
                                                                                ->numeric()
                                                                                ->default(32),
                                                                            \Filament\Forms\Components\Textarea::make('items_json')
                                                                                ->label('Items JSON')
                                                                                ->rows(4),
                                                                            \Filament\Forms\Components\Textarea::make('custom_html')
                                                                                ->label('Custom HTML')
                                                                                ->rows(5),
                                                                            \Filament\Forms\Components\Toggle::make('is_active')
                                                                                ->default(true),
                                                                            \Filament\Forms\Components\TextInput::make('sort_order')
                                                                                ->numeric()
                                                                                ->default(0),
                                                                        ])
                                                                        ->columns(3)
                                                                        ->collapsible()
                                                                        ->reorderable()
                                                                        ->default([]),
                                                                ])
                                                                ->columns(2)
                                                                ->collapsible()
                                                                ->reorderable()
                                                                ->default([]),
                                                        ])
                                                        ->columns(2)
                                                        ->collapsible()
                                                        ->reorderable()
                                                        ->default([]),
                                                ])
                                                ->columns(3)
                                                ->collapsible()
                                                ->reorderable()
                                                ->default([]),
                                        ]),

                                    \Filament\Schemas\Components\Section::make('Header Controls')
                                        ->schema([
                                            \Filament\Forms\Components\Toggle::make('layout_show_admin_login')
                                                ->label('Show Admin Login Button')
                                                ->default(true),
                                            \Filament\Forms\Components\TextInput::make('layout_admin_login_label')
                                                ->label('Admin Login Label')
                                                ->maxLength(255),
                                            \Filament\Forms\Components\TextInput::make('layout_admin_login_url')
                                                ->label('Admin Login URL')
                                                ->maxLength(255),
                                        ])->columns(3),

                                    \Filament\Schemas\Components\Section::make('Header Menu Links')
                                        ->description('Optional override. If empty, existing menu/fallback pages are used.')
                                        ->schema([
                                            \Filament\Forms\Components\Repeater::make('layout_header_menu_links')
                                                ->schema([
                                                    \Filament\Forms\Components\TextInput::make('label')
                                                        ->required()
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\TextInput::make('href')
                                                        ->required()
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\Select::make('target')
                                                        ->options([
                                                            '_self' => 'Same Tab',
                                                            '_blank' => 'New Tab',
                                                        ])
                                                        ->default('_self'),
                                                    \Filament\Forms\Components\Toggle::make('is_active')
                                                        ->default(true),
                                                    \Filament\Forms\Components\TextInput::make('sort_order')
                                                        ->numeric()
                                                        ->default(0),
                                                ])
                                                ->columns(5)
                                                ->reorderable()
                                                ->collapsible()
                                                ->default([]),
                                        ]),

                                    \Filament\Schemas\Components\Section::make('Showcase Placement')
                                        ->schema([
                                            \Filament\Forms\Components\Toggle::make('layout_show_header_showcases')
                                                ->label('Render Header Placement')
                                                ->default(true),
                                            \Filament\Forms\Components\Toggle::make('layout_show_home_showcases')
                                                ->label('Render Home Placement')
                                                ->default(true),
                                            \Filament\Forms\Components\Toggle::make('layout_show_sidebar_showcases')
                                                ->label('Render Sidebar Placement')
                                                ->default(true),
                                            \Filament\Forms\Components\Toggle::make('layout_show_footer_showcases')
                                                ->label('Render Footer Placement')
                                                ->default(true),
                                            \Filament\Forms\Components\Repeater::make('layout_showcases')
                                                ->schema([
                                                    \Filament\Forms\Components\Select::make('menu_placement')
                                                        ->options([
                                                            'header' => 'Header',
                                                            'home' => 'Home',
                                                            'sidebar' => 'Sidebar',
                                                            'footer' => 'Footer',
                                                        ])
                                                        ->default('home')
                                                        ->required(),
                                                    \Filament\Forms\Components\TextInput::make('eyebrow')
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\TextInput::make('title')
                                                        ->required()
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\Textarea::make('description')
                                                        ->rows(3),
                                                    \Filament\Forms\Components\TextInput::make('ctaLabel')
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\TextInput::make('ctaHref')
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\TextInput::make('secondaryLabel')
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\TextInput::make('secondaryHref')
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\TextInput::make('statLabel')
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\TextInput::make('statValue')
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\Select::make('accent')
                                                        ->options([
                                                            'blue' => 'Blue',
                                                            'emerald' => 'Emerald',
                                                            'purple' => 'Purple',
                                                            'orange' => 'Orange',
                                                            'gray' => 'Gray',
                                                        ])
                                                        ->default('blue'),
                                                    \Filament\Forms\Components\Toggle::make('is_active')
                                                        ->default(true),
                                                    \Filament\Forms\Components\TextInput::make('sort_order')
                                                        ->numeric()
                                                        ->default(0),
                                                ])
                                                ->columns(3)
                                                ->collapsible()
                                                ->reorderable()
                                                ->default([]),
                                        ])->columns(2),

                                    \Filament\Schemas\Components\Section::make('Module Builder')
                                        ->description('Build dynamic layout sections and choose where each module appears.')
                                        ->schema([
                                            \Filament\Forms\Components\Repeater::make('layout_modules')
                                                ->schema([
                                                    \Filament\Forms\Components\TextInput::make('id')
                                                        ->label('Module ID')
                                                        ->maxLength(100)
                                                        ->helperText('Optional stable identifier for this block.'),
                                                    \Filament\Forms\Components\Select::make('module_type')
                                                        ->required()
                                                        ->options([
                                                            'text' => 'Text Block',
                                                            'hero' => 'Hero Section',
                                                            'cta' => 'CTA Banner',
                                                            'stats' => 'Stats Grid',
                                                            'cards' => 'Cards Grid',
                                                            'custom_html' => 'Custom HTML',
                                                        ])
                                                        ->default('text'),
                                                    \Filament\Forms\Components\Select::make('placement')
                                                        ->required()
                                                        ->options([
                                                            'header_before' => 'Header: Before',
                                                            'header_after' => 'Header: After',
                                                            'main_before' => 'Main: Before Content',
                                                            'main_after' => 'Main: After Content',
                                                            'sidebar' => 'Sidebar Area',
                                                            'footer_before' => 'Footer: Before',
                                                            'footer_after' => 'Footer: After',
                                                        ])
                                                        ->default('main_after'),
                                                    \Filament\Forms\Components\Toggle::make('is_active')
                                                        ->default(true),
                                                    \Filament\Forms\Components\TextInput::make('sort_order')
                                                        ->numeric()
                                                        ->default(0),
                                                    \Filament\Forms\Components\TextInput::make('badge')
                                                        ->label('Eyebrow/Badge')
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\TextInput::make('title')
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\Textarea::make('subtitle')
                                                        ->rows(2),
                                                    \Filament\Forms\Components\Textarea::make('content')
                                                        ->rows(4),
                                                    \Filament\Forms\Components\TextInput::make('cta_label')
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\TextInput::make('cta_href')
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\TextInput::make('secondary_label')
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\TextInput::make('secondary_href')
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\Select::make('accent')
                                                        ->options([
                                                            'blue' => 'Blue',
                                                            'emerald' => 'Emerald',
                                                            'purple' => 'Purple',
                                                            'orange' => 'Orange',
                                                            'gray' => 'Gray',
                                                        ])
                                                        ->default('blue'),
                                                    \Filament\Forms\Components\Textarea::make('items_json')
                                                        ->label('Items JSON')
                                                        ->rows(6)
                                                        ->helperText('Used by stats/cards modules. Provide JSON array of items.'),
                                                    \Filament\Forms\Components\Textarea::make('custom_html')
                                                        ->label('Custom HTML')
                                                        ->rows(8)
                                                        ->helperText('Rendered only for Custom HTML module type.'),
                                                ])
                                                ->columns(3)
                                                ->collapsible()
                                                ->reorderable()
                                                ->default([]),
                                        ]),

                                    \Filament\Schemas\Components\Section::make('Section-wise Layout Builder')
                                        ->description('Create full sections and place module blocks inside each section.')
                                        ->schema([
                                            \Filament\Forms\Components\Repeater::make('layout_sections')
                                                ->schema([
                                                    \Filament\Forms\Components\TextInput::make('section_key')
                                                        ->label('Section Key')
                                                        ->required()
                                                        ->maxLength(100),
                                                    \Filament\Forms\Components\TextInput::make('section_title')
                                                        ->label('Section Title')
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\Textarea::make('section_subtitle')
                                                        ->label('Section Subtitle')
                                                        ->rows(2),
                                                    \Filament\Forms\Components\Select::make('placement')
                                                        ->required()
                                                        ->options([
                                                            'header_before' => 'Header: Before',
                                                            'header_after' => 'Header: After',
                                                            'main_before' => 'Main: Before Content',
                                                            'main_after' => 'Main: After Content',
                                                            'sidebar' => 'Sidebar',
                                                            'footer_before' => 'Footer: Before',
                                                            'footer_after' => 'Footer: After',
                                                        ])
                                                        ->default('main_after'),
                                                    \Filament\Forms\Components\Select::make('columns')
                                                        ->options([
                                                            '1' => '1 Column',
                                                            '2' => '2 Columns',
                                                            '3' => '3 Columns',
                                                            '4' => '4 Columns',
                                                        ])
                                                        ->default('2'),
                                                    \Filament\Forms\Components\TextInput::make('container_class')
                                                        ->label('Container Class')
                                                        ->placeholder('optional CSS utility classes')
                                                        ->maxLength(255),
                                                    \Filament\Forms\Components\TextInput::make('background_style')
                                                        ->label('Background Style')
                                                        ->placeholder('e.g. linear-gradient(...)')
                                                        ->maxLength(500),
                                                    \Filament\Forms\Components\TextInput::make('padding_top')
                                                        ->label('Padding Top (px)')
                                                        ->numeric()
                                                        ->default(20),
                                                    \Filament\Forms\Components\TextInput::make('padding_bottom')
                                                        ->label('Padding Bottom (px)')
                                                        ->numeric()
                                                        ->default(24),
                                                    \Filament\Forms\Components\Toggle::make('is_active')
                                                        ->default(true),
                                                    \Filament\Forms\Components\TextInput::make('sort_order')
                                                        ->numeric()
                                                        ->default(0),
                                                    \Filament\Forms\Components\Repeater::make('modules')
                                                        ->schema([
                                                            \Filament\Forms\Components\TextInput::make('id')
                                                                ->label('Module ID')
                                                                ->maxLength(100),
                                                            \Filament\Forms\Components\Select::make('module_type')
                                                                ->required()
                                                                ->options([
                                                                    'text' => 'Text Block',
                                                                    'hero' => 'Hero',
                                                                    'cta' => 'CTA',
                                                                    'stats' => 'Stats',
                                                                    'cards' => 'Cards',
                                                                    'custom_html' => 'Custom HTML',
                                                                ])
                                                                ->default('text'),
                                                            \Filament\Forms\Components\TextInput::make('title')
                                                                ->maxLength(255),
                                                            \Filament\Forms\Components\Textarea::make('subtitle')
                                                                ->rows(2),
                                                            \Filament\Forms\Components\Textarea::make('content')
                                                                ->rows(3),
                                                            \Filament\Forms\Components\TextInput::make('badge')
                                                                ->maxLength(255),
                                                            \Filament\Forms\Components\TextInput::make('cta_label')
                                                                ->maxLength(255),
                                                            \Filament\Forms\Components\TextInput::make('cta_href')
                                                                ->maxLength(255),
                                                            \Filament\Forms\Components\TextInput::make('secondary_label')
                                                                ->maxLength(255),
                                                            \Filament\Forms\Components\TextInput::make('secondary_href')
                                                                ->maxLength(255),
                                                            \Filament\Forms\Components\Select::make('accent')
                                                                ->options([
                                                                    'blue' => 'Blue',
                                                                    'emerald' => 'Emerald',
                                                                    'purple' => 'Purple',
                                                                    'orange' => 'Orange',
                                                                    'gray' => 'Gray',
                                                                ])
                                                                ->default('blue'),
                                                            \Filament\Forms\Components\Textarea::make('items_json')
                                                                ->label('Items JSON')
                                                                ->rows(5),
                                                            \Filament\Forms\Components\Textarea::make('custom_html')
                                                                ->label('Custom HTML')
                                                                ->rows(6),
                                                            \Filament\Forms\Components\Toggle::make('is_active')
                                                                ->default(true),
                                                            \Filament\Forms\Components\TextInput::make('sort_order')
                                                                ->numeric()
                                                                ->default(0),
                                                        ])
                                                        ->columns(3)
                                                        ->collapsible()
                                                        ->reorderable()
                                                        ->default([]),
                                                ])
                                                ->columns(3)
                                                ->collapsible()
                                                ->reorderable()
                                                ->default([]),
                                        ]),
                                ]),
                            Tabs\Tab::make('SEO')
                                ->schema([
                                    \Filament\Forms\Components\TextInput::make('seo_meta_title')
                                        ->label('Default Meta Title')
                                        ->maxLength(255),
                                    \Filament\Forms\Components\Textarea::make('seo_meta_description')
                                        ->label('Default Meta Description')
                                        ->rows(4),
                                    \Filament\Forms\Components\TextInput::make('seo_meta_keywords')
                                        ->label('Default Meta Keywords')
                                        ->helperText('Comma-separated keywords used as fallback.')
                                        ->maxLength(500),
                                    \Filament\Forms\Components\Select::make('seo_meta_robots')
                                        ->label('Robots Default')
                                        ->options([
                                            'index,follow' => 'Index, Follow',
                                            'noindex,follow' => 'No Index, Follow',
                                            'index,nofollow' => 'Index, No Follow',
                                            'noindex,nofollow' => 'No Index, No Follow',
                                        ])
                                        ->default('index,follow'),
                                    \Filament\Forms\Components\TextInput::make('seo_og_type')
                                        ->label('Open Graph Type')
                                        ->default('website')
                                        ->maxLength(50),
                                    \Filament\Forms\Components\TextInput::make('seo_og_image')
                                        ->label('Default OG Image URL')
                                        ->url()
                                        ->maxLength(255),
                                    \Filament\Forms\Components\Select::make('seo_twitter_card')
                                        ->label('Twitter Card Type')
                                        ->options([
                                            'summary' => 'Summary',
                                            'summary_large_image' => 'Summary Large Image',
                                        ])
                                        ->default('summary_large_image'),
                                    \Filament\Forms\Components\TextInput::make('seo_twitter_site')
                                        ->label('Twitter Site Handle')
                                        ->placeholder('@yourbrand')
                                        ->maxLength(100),
                                    \Filament\Forms\Components\TextInput::make('seo_twitter_creator')
                                        ->label('Twitter Creator Handle')
                                        ->placeholder('@editor')
                                        ->maxLength(100),
                                    \Filament\Forms\Components\TextInput::make('seo_twitter_image')
                                        ->label('Default Twitter Image URL')
                                        ->url()
                                        ->maxLength(255),
                                    \Filament\Forms\Components\TextInput::make('seo_theme_color')
                                        ->label('Theme Color')
                                        ->placeholder('#0b1020')
                                        ->maxLength(20),
                                    \Filament\Forms\Components\TextInput::make('site_favicon_url')
                                        ->label('Favicon URL')
                                        ->url()
                                        ->maxLength(255),
                                    \Filament\Forms\Components\TextInput::make('site_apple_touch_icon_url')
                                        ->label('Apple Touch Icon URL')
                                        ->url()
                                        ->maxLength(255),
                                ]),
                            Tabs\Tab::make('SEO Verification')
                                ->schema([
                                    \Filament\Forms\Components\TextInput::make('seo_google_verification')
                                        ->label('Google Site Verification')
                                        ->maxLength(255),
                                    \Filament\Forms\Components\TextInput::make('seo_bing_verification')
                                        ->label('Bing Site Verification')
                                        ->maxLength(255),
                                    \Filament\Forms\Components\TextInput::make('seo_pinterest_verification')
                                        ->label('Pinterest Site Verification')
                                        ->maxLength(255),
                                ]),
                            Tabs\Tab::make('SEO Schema')
                                ->schema([
                                    \Filament\Forms\Components\Textarea::make('seo_organization_schema')
                                        ->label('Organization JSON-LD')
                                        ->helperText('Paste valid Organization schema JSON (without <script> tag).')
                                        ->rows(10),
                                ]),
                        ]),
                ]),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action(fn () => $this->save()),
        ];
    }

    public function save(): void
    {
        $this->validate([
            'site_title' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string'],
            'site_email' => ['nullable', 'email', 'max:255'],
            'site_phone' => ['nullable', 'string', 'max:50'],
            'site_address' => ['nullable', 'string'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'homepage_hero_title' => ['nullable', 'string', 'max:255'],
            'homepage_hero_subtitle' => ['nullable', 'string'],
            'homepage_cta_text' => ['nullable', 'string', 'max:255'],
            'homepage_cta_url' => ['nullable', 'string', 'max:255'],
            'seo_meta_title' => ['nullable', 'string', 'max:255'],
            'seo_meta_description' => ['nullable', 'string'],
            'seo_meta_keywords' => ['nullable', 'string', 'max:500'],
            'seo_meta_robots' => ['nullable', 'string', 'max:50'],
            'seo_og_type' => ['nullable', 'string', 'max:50'],
            'seo_og_image' => ['nullable', 'url', 'max:255'],
            'seo_twitter_card' => ['nullable', 'string', 'max:50'],
            'seo_twitter_site' => ['nullable', 'string', 'max:100'],
            'seo_twitter_creator' => ['nullable', 'string', 'max:100'],
            'seo_twitter_image' => ['nullable', 'url', 'max:255'],
            'seo_google_verification' => ['nullable', 'string', 'max:255'],
            'seo_bing_verification' => ['nullable', 'string', 'max:255'],
            'seo_pinterest_verification' => ['nullable', 'string', 'max:255'],
            'seo_theme_color' => ['nullable', 'string', 'max:20'],
            'site_favicon_url' => ['nullable', 'url', 'max:255'],
            'site_apple_touch_icon_url' => ['nullable', 'url', 'max:255'],
            'seo_organization_schema' => ['nullable', 'string'],
            'layout_show_admin_login' => ['boolean'],
            'layout_admin_login_label' => ['nullable', 'string', 'max:255'],
            'layout_admin_login_url' => ['nullable', 'string', 'max:255'],
            'layout_show_header_showcases' => ['boolean'],
            'layout_show_home_showcases' => ['boolean'],
            'layout_show_sidebar_showcases' => ['boolean'],
            'layout_show_footer_showcases' => ['boolean'],
            'layout_header_menu_links' => ['array'],
            'layout_header_menu_links.*.label' => ['required_with:layout_header_menu_links', 'string', 'max:255'],
            'layout_header_menu_links.*.href' => ['required_with:layout_header_menu_links', 'string', 'max:255'],
            'layout_header_menu_links.*.target' => ['nullable', 'string', 'max:20'],
            'layout_header_menu_links.*.is_active' => ['boolean'],
            'layout_header_menu_links.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'layout_showcases' => ['array'],
            'layout_showcases.*.menu_placement' => ['required_with:layout_showcases', 'string', 'max:50'],
            'layout_showcases.*.eyebrow' => ['nullable', 'string', 'max:255'],
            'layout_showcases.*.title' => ['required_with:layout_showcases', 'string', 'max:255'],
            'layout_showcases.*.description' => ['nullable', 'string'],
            'layout_showcases.*.ctaLabel' => ['nullable', 'string', 'max:255'],
            'layout_showcases.*.ctaHref' => ['nullable', 'string', 'max:255'],
            'layout_showcases.*.secondaryLabel' => ['nullable', 'string', 'max:255'],
            'layout_showcases.*.secondaryHref' => ['nullable', 'string', 'max:255'],
            'layout_showcases.*.statLabel' => ['nullable', 'string', 'max:255'],
            'layout_showcases.*.statValue' => ['nullable', 'string', 'max:255'],
            'layout_showcases.*.accent' => ['nullable', 'string', 'max:50'],
            'layout_showcases.*.is_active' => ['boolean'],
            'layout_showcases.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'layout_modules' => ['array'],
            'layout_modules.*.id' => ['nullable', 'string', 'max:100'],
            'layout_modules.*.module_type' => ['required_with:layout_modules', 'string', 'max:50'],
            'layout_modules.*.placement' => ['required_with:layout_modules', 'string', 'max:50'],
            'layout_modules.*.is_active' => ['boolean'],
            'layout_modules.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'layout_modules.*.badge' => ['nullable', 'string', 'max:255'],
            'layout_modules.*.title' => ['nullable', 'string', 'max:255'],
            'layout_modules.*.subtitle' => ['nullable', 'string'],
            'layout_modules.*.content' => ['nullable', 'string'],
            'layout_modules.*.cta_label' => ['nullable', 'string', 'max:255'],
            'layout_modules.*.cta_href' => ['nullable', 'string', 'max:255'],
            'layout_modules.*.secondary_label' => ['nullable', 'string', 'max:255'],
            'layout_modules.*.secondary_href' => ['nullable', 'string', 'max:255'],
            'layout_modules.*.accent' => ['nullable', 'string', 'max:50'],
            'layout_modules.*.items_json' => ['nullable', 'string'],
            'layout_modules.*.custom_html' => ['nullable', 'string'],
            'layout_sections' => ['array'],
            'layout_sections.*.section_key' => ['required_with:layout_sections', 'string', 'max:100'],
            'layout_sections.*.section_title' => ['nullable', 'string', 'max:255'],
            'layout_sections.*.section_subtitle' => ['nullable', 'string'],
            'layout_sections.*.placement' => ['required_with:layout_sections', 'string', 'max:50'],
            'layout_sections.*.columns' => ['nullable', 'string', 'max:10'],
            'layout_sections.*.container_class' => ['nullable', 'string', 'max:255'],
            'layout_sections.*.background_style' => ['nullable', 'string', 'max:500'],
            'layout_sections.*.padding_top' => ['nullable', 'integer', 'min:0', 'max:500'],
            'layout_sections.*.padding_bottom' => ['nullable', 'integer', 'min:0', 'max:500'],
            'layout_sections.*.is_active' => ['boolean'],
            'layout_sections.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'layout_sections.*.modules' => ['array'],
            'layout_sections.*.modules.*.id' => ['nullable', 'string', 'max:100'],
            'layout_sections.*.modules.*.module_type' => ['required_with:layout_sections.*.modules', 'string', 'max:50'],
            'layout_sections.*.modules.*.title' => ['nullable', 'string', 'max:255'],
            'layout_sections.*.modules.*.subtitle' => ['nullable', 'string'],
            'layout_sections.*.modules.*.content' => ['nullable', 'string'],
            'layout_sections.*.modules.*.badge' => ['nullable', 'string', 'max:255'],
            'layout_sections.*.modules.*.cta_label' => ['nullable', 'string', 'max:255'],
            'layout_sections.*.modules.*.cta_href' => ['nullable', 'string', 'max:255'],
            'layout_sections.*.modules.*.secondary_label' => ['nullable', 'string', 'max:255'],
            'layout_sections.*.modules.*.secondary_href' => ['nullable', 'string', 'max:255'],
            'layout_sections.*.modules.*.accent' => ['nullable', 'string', 'max:50'],
            'layout_sections.*.modules.*.items_json' => ['nullable', 'string'],
            'layout_sections.*.modules.*.custom_html' => ['nullable', 'string'],
            'layout_sections.*.modules.*.is_active' => ['boolean'],
            'layout_sections.*.modules.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if (filled($this->seo_organization_schema)) {
            json_decode($this->seo_organization_schema, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Notification::make()
                    ->title('Invalid Organization JSON-LD')
                    ->body('Please provide valid JSON before saving SEO schema.')
                    ->danger()
                    ->send();

                return;
            }
        }

        $this->upsertSetting('site_title', $this->site_title, 'string', 'general', 'Website title', true);
        $this->upsertSetting('site_tagline', $this->site_tagline, 'string', 'general', 'Website tagline', true);
        $this->upsertSetting('site_description', $this->site_description, 'text', 'general', 'Website description', true);

        $this->upsertSetting('site_email', $this->site_email, 'string', 'contact', 'Website contact email', true);
        $this->upsertSetting('site_phone', $this->site_phone, 'string', 'contact', 'Website contact phone', true);
        $this->upsertSetting('site_address', $this->site_address, 'text', 'contact', 'Website address', true);

        $this->upsertSetting('facebook_url', $this->facebook_url, 'string', 'social', 'Facebook URL', true);
        $this->upsertSetting('instagram_url', $this->instagram_url, 'string', 'social', 'Instagram URL', true);
        $this->upsertSetting('youtube_url', $this->youtube_url, 'string', 'social', 'YouTube URL', true);
        $this->upsertSetting('linkedin_url', $this->linkedin_url, 'string', 'social', 'LinkedIn URL', true);

        $this->upsertSetting('homepage_hero_title', $this->homepage_hero_title, 'string', 'homepage', 'Homepage hero title', true);
        $this->upsertSetting('homepage_hero_subtitle', $this->homepage_hero_subtitle, 'text', 'homepage', 'Homepage hero subtitle', true);
        $this->upsertSetting('homepage_cta_text', $this->homepage_cta_text, 'string', 'homepage', 'Homepage CTA text', true);
        $this->upsertSetting('homepage_cta_url', $this->homepage_cta_url, 'string', 'homepage', 'Homepage CTA URL', true);

        $this->upsertSetting('seo_meta_title', $this->seo_meta_title, 'string', 'seo', 'Default SEO title', true);
        $this->upsertSetting('seo_meta_description', $this->seo_meta_description, 'text', 'seo', 'Default SEO description', true);
        $this->upsertSetting('seo_meta_keywords', $this->seo_meta_keywords, 'string', 'seo', 'Default SEO keywords', true);
        $this->upsertSetting('seo_meta_robots', $this->seo_meta_robots, 'string', 'seo', 'Default robots directive', true);
        $this->upsertSetting('seo_og_type', $this->seo_og_type, 'string', 'seo', 'Default Open Graph type', true);
        $this->upsertSetting('seo_og_image', $this->seo_og_image, 'string', 'seo', 'Default Open Graph image URL', true);
        $this->upsertSetting('seo_twitter_card', $this->seo_twitter_card, 'string', 'seo', 'Twitter card type', true);
        $this->upsertSetting('seo_twitter_site', $this->seo_twitter_site, 'string', 'seo', 'Twitter site handle', true);
        $this->upsertSetting('seo_twitter_creator', $this->seo_twitter_creator, 'string', 'seo', 'Twitter creator handle', true);
        $this->upsertSetting('seo_twitter_image', $this->seo_twitter_image, 'string', 'seo', 'Default Twitter image URL', true);
        $this->upsertSetting('seo_google_verification', $this->seo_google_verification, 'string', 'seo', 'Google site verification', true);
        $this->upsertSetting('seo_bing_verification', $this->seo_bing_verification, 'string', 'seo', 'Bing site verification', true);
        $this->upsertSetting('seo_pinterest_verification', $this->seo_pinterest_verification, 'string', 'seo', 'Pinterest site verification', true);
        $this->upsertSetting('seo_theme_color', $this->seo_theme_color, 'string', 'seo', 'Theme color', true);
        $this->upsertSetting('site_favicon_url', $this->site_favicon_url, 'string', 'seo', 'Favicon URL', true);
        $this->upsertSetting('site_apple_touch_icon_url', $this->site_apple_touch_icon_url, 'string', 'seo', 'Apple touch icon URL', true);
        $this->upsertSetting('seo_organization_schema', $this->seo_organization_schema, 'text', 'seo', 'Organization schema JSON-LD', false);

        $this->upsertSetting('layout_show_admin_login', $this->layout_show_admin_login, 'boolean', 'layout', 'Show admin login button', false);
        $this->upsertSetting('layout_admin_login_label', $this->layout_admin_login_label, 'string', 'layout', 'Admin login label', false);
        $this->upsertSetting('layout_admin_login_url', $this->layout_admin_login_url, 'string', 'layout', 'Admin login URL', false);
        $this->upsertSetting('layout_show_header_showcases', $this->layout_show_header_showcases, 'boolean', 'layout', 'Render header showcases', false);
        $this->upsertSetting('layout_show_home_showcases', $this->layout_show_home_showcases, 'boolean', 'layout', 'Render home showcases', false);
        $this->upsertSetting('layout_show_sidebar_showcases', $this->layout_show_sidebar_showcases, 'boolean', 'layout', 'Render sidebar showcases', false);
        $this->upsertSetting('layout_show_footer_showcases', $this->layout_show_footer_showcases, 'boolean', 'layout', 'Render footer showcases', false);
        $this->upsertSetting('layout_header_menu_links', $this->layout_header_menu_links, 'json', 'layout', 'Header menu links override', false);
        $this->upsertSetting('layout_showcases', $this->layout_showcases, 'json', 'layout', 'Showcase cards and menu placement', false);
        $this->upsertSetting('layout_modules', $this->layout_modules, 'json', 'layout', 'Dynamic layout module builder blocks', false);
        $this->upsertSetting('layout_sections', $this->layout_sections, 'json', 'layout', 'Section-wise layout builder blocks', false);

        Notification::make()
            ->title('Website settings saved')
            ->success()
            ->send();
    }

    private function getSetting(string $key, mixed $default = null): mixed
    {
        return WebsiteSettings::query()->where('key', $key)->value('value') ?? $default;
    }

    private function getJsonSetting(string $key, array $default = []): array
    {
        $value = WebsiteSettings::query()->where('key', $key)->value('value');

        if (!is_string($value) || trim($value) === '') {
            return $default;
        }

        $decoded = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            return $default;
        }

        return $decoded;
    }

    private function upsertSetting(
        string $key,
        mixed $value,
        string $type,
        ?string $group = null,
        ?string $description = null,
        bool $isPublic = false,
    ): void {
        $storedValue = $value;

        if ($type === 'json') {
            $storedValue = json_encode($value ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        WebsiteSettings::query()->updateOrCreate(
            ['key' => $key],
            [
                'value' => $storedValue,
                'type' => $type,
                'group' => $group,
                'description' => $description,
                'is_public' => $isPublic,
            ],
        );
    }
}

