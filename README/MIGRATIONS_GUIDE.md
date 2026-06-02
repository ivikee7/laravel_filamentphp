# Website Database Migrations - Filament PHP 5 Structure

## Migration Files (in execution order)

### 1. **2026_05_28_121326_create_website_settings_table.php**
- Table: `website_settings`
- Stores global key-value configuration
- Columns: id, key, value, type, group, description, is_public, timestamps
- Indexes: [group, is_public]

### 2. **2026_05_28_121347_create_website_menus_table.php**
- Table: `website_menus`
- Defines named menu locations
- Columns: id, name, slug, location, description, is_active, sort_order, timestamps
- Indexes: [location, is_active]

### 3. **2026_05_28_121355_create_website_menu_items_table.php**
- Table: `website_menu_items`
- Individual menu items with hierarchy
- Columns: id, website_menu_id (FK), website_page_id (unsigned), parent_id (FK self-ref), label, url, target, icon, is_active, sort_order, timestamps
- Foreign Keys:
  - website_menu_id → website_menus (cascade)
  - parent_id → website_menu_items (null on delete)
- website_page_id is unsigned - FK added in separate migration

### 4. **2026_05_28_121413_create_website_categories_table.php**
- Table: `website_categories`
- Hierarchical page categories
- Columns: id, name, slug, description, parent_id (FK self-ref), is_active, sort_order, timestamps
- Foreign Keys:
  - parent_id → website_categories (null on delete)

### 5. **2026_05_28_121417_create_website_tags_table.php**
- Table: `website_tags`
- Page tags for classification
- Columns: id, name, slug, description, is_active, timestamps
- Indexes: [is_active]

### 6. **2026_05_28_121436_create_website_pages_table.php**
- Table: `website_pages`
- Main content pages
- Columns: id, title, slug, excerpt, content, template, website_category_id (FK), status, is_home, show_in_menu, sort_order, meta_title, meta_description, published_at, timestamps
- Foreign Keys:
  - website_category_id → website_categories (null on delete)

### 7. **2026_05_28_121500_add_website_page_foreign_key_to_menu_items.php**
- Adds deferred foreign key constraint
- Adds: website_page_id → website_pages (null on delete)
- Runs AFTER website_pages table is created

## Key Features of This Structure

✅ **Filament PHP 5 Anonymous Classes**
```php
return new class extends Migration
{
    public function up(): void { }
    public function down(): void { }
};
```

✅ **Guard Clauses** - Prevent error on re-runs
```php
if (Schema::hasTable('table_name')) {
    return;
}
```

✅ **Foreign Key Ordering**
- Deferred FK for website_page_id added in separate migration
- Ensures website_pages table exists before constraint

✅ **Self-Referential FKs**
- parent_id fields use self-references for hierarchy
- Enabled for categories and menu items

✅ **Strategic Indexes**
- Composite indexes for common query patterns
- Single indexes for frequently filtered columns

✅ **Cascade Behaviors**
- CASCADE: Direct relationships (menu_id, category_id)
- NULL ON DELETE: Optional relationships (parent_id, page_id)

## Migration Execution Order

The migrations are ordered by timestamp:
```
1. 2026_05_28_121326 - Settings
2. 2026_05_28_121347 - Menus
3. 2026_05_28_121355 - Menu Items (with website_page_id as unsigned)
4. 2026_05_28_121413 - Categories
5. 2026_05_28_121417 - Tags
6. 2026_05_28_121436 - Pages
7. 2026_05_28_121500 - Add FK to Menu Items
```

## Running Migrations

```bash
# Fresh migration (drops all tables then migrates)
php artisan migrate:fresh

# With seeding
php artisan migrate:fresh --seed

# Fresh with specific seeder
php artisan migrate:fresh --seed --seeder=WebsiteSeeder

# Migrate only
php artisan migrate

# Rollback
php artisan migrate:rollback
```

## Database Relationships Summary

```
website_menus
├── HasMany: website_menu_items

website_menu_items
├── BelongsTo: website_menu
├── BelongsTo: website_pages (nullable)
├── BelongsTo: website_menu_items (parent, nullable)
└── HasMany: website_menu_items (children)

website_categories
├── BelongsTo: website_categories (parent, nullable)
├── HasMany: website_categories (children)
└── HasMany: website_pages

website_pages
└── BelongsTo: website_categories (nullable)

website_settings
└── (No relationships - key-value storage)

website_tags
└── (No relationships - simple tags)
```

## Notes

- All migrations include proper error handling with guard clauses
- Foreign key constraints optimized for data integrity
- Deferred FK allows proper table creation order
- Indexes optimize query performance
- Ready for production use with Filament PHP 5

