<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('website_menu_items')) {
            return;
        }

        Schema::create('website_menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_menu_id')->constrained('website_menus')->cascadeOnDelete();
            $table->unsignedBigInteger('website_page_id')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('website_menu_items')->nullOnDelete();
            $table->string('label');
            $table->string('url')->nullable();
            $table->string('target')->default('_self');
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['website_menu_id', 'parent_id']);
            $table->index(['website_page_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_menu_items');
    }
};
