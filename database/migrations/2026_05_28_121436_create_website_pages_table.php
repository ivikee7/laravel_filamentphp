<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('website_pages')) {
            return;
        }

        Schema::create('website_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('template')->nullable();
            $table->foreignId('website_category_id')->nullable()->constrained('website_categories')->nullOnDelete();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_home')->default(false);
            $table->boolean('show_in_menu')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index(['website_category_id', 'sort_order']);
        });

        // Add deferred foreign key constraint to website_menu_items
        if (Schema::hasTable('website_menu_items')) {
            Schema::table('website_menu_items', function (Blueprint $table) {
                $table->foreign('website_page_id')
                    ->references('id')
                    ->on('website_pages')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key from website_menu_items if it exists
        if (Schema::hasTable('website_menu_items')) {
            Schema::table('website_menu_items', function (Blueprint $table) {
                try {
                    $table->dropForeign(['website_page_id']);
                } catch (\Exception $e) {
                    // Constraint may not exist
                }
            });
        }

        Schema::dropIfExists('website_pages');
    }
};
