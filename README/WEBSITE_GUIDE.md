# Website Management Quick Reference

## Admin Panel Navigation

The website management features are located in the Filament Admin panel under the **Website** navigation group:

### 1. **Website > Pages** 
   - **Create/Edit/Delete** pages
   - Set home page with `is_home` toggle
   - Control visibility with `show_in_menu`
   - Manage publishing status (draft/published/archived)
   - SEO optimization

### 2. **Website > Categories**
   - Organize pages hierarchically
   - Set parent-child relationships
   - Control active status

### 3. **Website > Tags**
   - Create and manage tags
   - Assign to pages for classification
   - Enable/disable tags

### 4. **Website > Menus**
   - Create named menus (header, footer, sidebar)
   - Manage menu items with hierarchy
   - Link to pages or custom URLs

### 5. **Website > Settings**
   - Configure global settings
   - Set key-value pairs with types
   - Manage public/private flags

## Common Tasks

### Create a New Page
```
1. Go to Website > Pages
2. Click Create
3. Enter:
   - Title: "About Us"
   - Slug: "about" (auto-generated from title)
   - Content: Use rich editor
   - Category: Select if needed
   - Status: Set to "published"
   - Meta Title/Description: For SEO
4. Click Save
```

### Set Home Page
```
1. Create or edit a page
2. Enable "is_home" toggle
3. Set status to "published"
4. Save

The home page will be displayed at / route
```

### Create Navigation Menu
```
1. Go to Website > Menus
2. Create menu:
   - Name: "Main Header"
   - Location: "header"
   - Active: On
3. Click Save
4. Add menu items by creating WebsiteMenuItem entries
5. Link items to pages
```

### Add Menu Items
```
1. Go to Website > Menus
2. Select menu
3. Create related WebsiteMenuItem
4. Set:
   - Label: "About"
   - URL or Page: Link to page
   - Parent: For sub-items
   - Sort Order: Numeric order
5. Save
```

### Configure Settings
```
1. Go to Website > Settings
2. Create setting:
   - Key: "site_title"
   - Value: "My School Name"
   - Type: "string"
   - Group: "general"
3. Save

Access in code:
WebsiteSettings::getValueByKey('site_title', 'Default')
```

## Frontend Access

### Homepage
- **URL**: `/`
- **Displays**: Page marked as `is_home = true`

### Page by Slug
- **URL**: `/{page_slug}`
- **Example**: `/about`, `/academics`, `/contact`
- **Displays**: Published page with matching slug

### Navigation
- **Header Menu**: Loaded from `website_menus` with location="header"
- **Footer Menu**: Loaded from `website_menus` with location="footer"

## Database Access

### Retrieve Homepage
```php
$homePage = WebsitePage::where('is_home', true)
    ->where('status', 'published')
    ->first();
```

### Get Page by Slug
```php
$page = WebsitePage::where('slug', 'about')
    ->where('status', 'published')
    ->first();
```

### Get Menu Items
```php
$menu = WebsiteMenu::where('location', 'header')
    ->where('is_active', true)
    ->with(['items' => function ($q) {
        $q->where('is_active', true)->orderBy('sort_order');
    }])
    ->first();
```

### Get Setting
```php
$siteTitle = WebsiteSettings::getValueByKey('site_title', 'My School');
```

## Seeded Data

Initial seeder includes:
- **Pages**: Home, About, Academics, Admissions, Contact
- **Categories**: About, Academics, Admissions
- **Tags**: Featured, Announcement
- **Menus**: Header (with all pages), Footer (Privacy, Terms, Sitemap)
- **Settings**: site_title, site_description, site_email

Run seeder:
```bash
php artisan db:seed --class=WebsiteSeeder
```

## Important Notes

1. **Page Slug** must be unique
2. **Menu Item Sort Order** determines display order
3. **Status Options**: draft (hidden), published (visible), archived
4. **Home Page**: Only one page should have `is_home = true`
5. **Foreign Keys**: Automatically handle cascading deletes
6. **Navigation**: Updated from database on each request

## Troubleshooting

### Page not showing on frontend
- Check status is "published"
- Verify slug matches URL
- Ensure `published_at` is set (if filtering by date)

### Menu not displaying
- Verify menu `is_active = true`
- Check menu items have `is_active = true`
- Confirm location matches layout rendering code

### Routes not working
- Clear route cache: `php artisan route:clear`
- Verify WebsiteController exists
- Check routes/website.php exists

### Seeder not running
- Navigate to project directory
- Run: `php artisan db:seed --class=WebsiteSeeder`
- Or run all: `php artisan db:seed`

