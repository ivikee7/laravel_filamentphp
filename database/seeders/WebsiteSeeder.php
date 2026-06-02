<?php

namespace Database\Seeders;

use App\Models\WebsiteSettings;
use App\Models\WebsiteCategory;
use App\Models\WebsiteMenu;
use App\Models\WebsiteMenuItem;
use App\Models\WebsitePage;
use App\Models\WebsiteShowcase;
use App\Models\WebsiteTag;
use Illuminate\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create website settings
        WebsiteSettings::create([
            'key' => 'site_title',
            'value' => 'Riverstone School',
            'type' => 'string',
            'group' => 'general',
            'description' => 'Site Title',
            'is_public' => true,
        ]);

        WebsiteSettings::create([
            'key' => 'site_description',
            'value' => 'A modern learning community for curious minds.',
            'type' => 'text',
            'group' => 'general',
            'description' => 'Site Meta Description',
            'is_public' => true,
        ]);

        WebsiteSettings::create([
            'key' => 'site_email',
            'value' => 'hello@riverstoneschool.edu',
            'type' => 'string',
            'group' => 'contact',
            'description' => 'Contact Email',
            'is_public' => true,
        ]);

        // Create categories
        $aboutCategory = WebsiteCategory::create([
            'name' => 'About',
            'slug' => 'about',
            'description' => 'About the school',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $academicsCategory = WebsiteCategory::create([
            'name' => 'Academics',
            'slug' => 'academics',
            'description' => 'Academic programs',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $admissionsCategory = WebsiteCategory::create([
            'name' => 'Admissions',
            'slug' => 'admissions',
            'description' => 'Admission process',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Create tags
        WebsiteTag::create([
            'name' => 'Featured',
            'slug' => 'featured',
            'description' => 'Featured content',
            'is_active' => true,
        ]);

        WebsiteTag::create([
            'name' => 'Announcement',
            'slug' => 'announcement',
            'description' => 'School announcements',
            'is_active' => true,
        ]);

        // Create pages
        $homePage = WebsitePage::create([
            'title' => 'Home',
            'slug' => 'home',
            'excerpt' => 'Welcome to Riverstone School',
            'content' => '<h2>Welcome to Riverstone School</h2><p>A modern learning community for curious minds.</p>',
            'status' => 'published',
            'is_home' => true,
            'show_in_menu' => false,
            'sort_order' => 0,
            'meta_title' => 'Riverstone School · Home',
            'meta_description' => 'A modern learning community for curious minds.',
            'published_at' => now(),
        ]);

        $aboutPage = WebsitePage::create([
            'title' => 'About Us',
            'slug' => 'about',
            'excerpt' => 'Learn about our school',
            'content' => '<h2>About Riverstone School</h2><p>We are a modern school combining strong academics, safe spaces, creative experiences, and student support.</p>',
            'website_category_id' => $aboutCategory->id,
            'status' => 'published',
            'is_home' => false,
            'show_in_menu' => true,
            'sort_order' => 1,
            'meta_title' => 'About Us · Riverstone School',
            'meta_description' => 'Learn about Riverstone School',
            'published_at' => now(),
        ]);

        $academicsPage = WebsitePage::create([
            'title' => 'Academics',
            'slug' => 'academics',
            'excerpt' => 'Explore our academic programs',
            'content' => '<h2>Academic Programs</h2><p>From foundational learning to senior school, each stage is designed with the right balance of academics, care, and enrichment.</p>',
            'website_category_id' => $academicsCategory->id,
            'status' => 'published',
            'is_home' => false,
            'show_in_menu' => true,
            'sort_order' => 2,
            'meta_title' => 'Academics · Riverstone School',
            'meta_description' => 'Explore our academic programs',
            'published_at' => now(),
        ]);

        $admissionsPage = WebsitePage::create([
            'title' => 'Admissions',
            'slug' => 'admissions',
            'excerpt' => 'Start your admission journey',
            'content' => '<h2>Admissions Open</h2><p>We welcome applications for all grades. Meet our teachers, explore the learning spaces, and get a clear admission plan for your child.</p>',
            'website_category_id' => $admissionsCategory->id,
            'status' => 'published',
            'is_home' => false,
            'show_in_menu' => true,
            'sort_order' => 3,
            'meta_title' => 'Admissions · Riverstone School',
            'meta_description' => 'Start your admission journey',
            'published_at' => now(),
        ]);

        $contactPage = WebsitePage::create([
            'title' => 'Contact',
            'slug' => 'contact',
            'excerpt' => 'Get in touch with us',
            'content' => '<h2>Contact Us</h2><p>Have questions? We are here to help. Contact our admissions office or schedule a campus visit.</p>',
            'status' => 'published',
            'is_home' => false,
            'show_in_menu' => true,
            'sort_order' => 4,
            'meta_title' => 'Contact · Riverstone School',
            'meta_description' => 'Get in touch with us',
            'published_at' => now(),
        ]);

        // Create main menu
        $headerMenu = WebsiteMenu::create([
            'name' => 'Main Header Menu',
            'slug' => 'main-header',
            'location' => 'header',
            'description' => 'Primary navigation menu',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Create menu items
        WebsiteMenuItem::create([
            'website_menu_id' => $headerMenu->id,
            'website_page_id' => $homePage->id,
            'parent_id' => null,
            'label' => 'Home',
            'url' => route('website.home'),
            'target' => '_self',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        WebsiteMenuItem::create([
            'website_menu_id' => $headerMenu->id,
            'website_page_id' => $aboutPage->id,
            'parent_id' => null,
            'label' => 'About',
            'url' => route('website.page', 'about'),
            'target' => '_self',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        WebsiteMenuItem::create([
            'website_menu_id' => $headerMenu->id,
            'website_page_id' => $academicsPage->id,
            'parent_id' => null,
            'label' => 'Academics',
            'url' => route('website.page', 'academics'),
            'target' => '_self',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        WebsiteMenuItem::create([
            'website_menu_id' => $headerMenu->id,
            'website_page_id' => $admissionsPage->id,
            'parent_id' => null,
            'label' => 'Admissions',
            'url' => route('website.page', 'admissions'),
            'target' => '_self',
            'is_active' => true,
            'sort_order' => 4,
        ]);

        WebsiteMenuItem::create([
            'website_menu_id' => $headerMenu->id,
            'website_page_id' => $contactPage->id,
            'parent_id' => null,
            'label' => 'Contact',
            'url' => route('website.page', 'contact'),
            'target' => '_self',
            'is_active' => true,
            'sort_order' => 5,
        ]);

        // Create footer menu
        $footerMenu = WebsiteMenu::create([
            'name' => 'Footer Menu',
            'slug' => 'main-footer',
            'location' => 'footer',
            'description' => 'Footer navigation menu',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        WebsiteMenuItem::create([
            'website_menu_id' => $footerMenu->id,
            'parent_id' => null,
            'label' => 'Privacy Policy',
            'url' => '#',
            'target' => '_self',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        WebsiteMenuItem::create([
            'website_menu_id' => $footerMenu->id,
            'parent_id' => null,
            'label' => 'Terms of Service',
            'url' => '#',
            'target' => '_self',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        WebsiteMenuItem::create([
            'website_menu_id' => $footerMenu->id,
            'parent_id' => null,
            'label' => 'Sitemap',
            'url' => '#',
            'target' => '_self',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        WebsiteShowcase::updateOrCreate(
            ['key' => 'updates-menu-placement'],
            [
                'menu_placement' => 'header',
                'payload' => [
                    'eyebrow' => 'Updates',
                    'title' => 'Keep announcements and circulars visible right from the first screen.',
                    'description' => 'Recent posts, job opportunities, circulars, and important notices stay one click away for parents and students.',
                    'ctaLabel' => 'Read Circulars',
                    'ctaHref' => '/pages/articles/circular/',
                    'secondaryLabel' => 'Latest Blogs',
                    'secondaryHref' => '/pages/articles/blogs/',
                    'statLabel' => 'Access',
                    'statValue' => 'Quick, organized, current',
                    'accent' => 'blue',
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
        );

        WebsiteShowcase::updateOrCreate(
            ['key' => 'school-life-menu-placement'],
            [
                'menu_placement' => 'header',
                'payload' => [
                    'eyebrow' => 'School Life',
                    'title' => 'See academics, activities, and infrastructure without digging through the site.',
                    'description' => 'Browse facilities, curriculum, holiday lists, annual reports, and beyond-academics pages in a single flow.',
                    'ctaLabel' => 'View Facilities',
                    'ctaHref' => '/pages/facilities/',
                    'secondaryLabel' => 'Browse Categories',
                    'secondaryHref' => '/pages/',
                    'statLabel' => 'Coverage',
                    'statValue' => 'Academics, sports, culture',
                    'accent' => 'emerald',
                ],
                'is_active' => true,
                'sort_order' => 2,
            ],
        );
    }
}

