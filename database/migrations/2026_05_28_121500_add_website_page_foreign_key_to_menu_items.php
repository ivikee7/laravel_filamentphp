<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds the foreign key constraint for website_page_id after website_pages table exists.
     */
    public function up(): void
    {
        Schema::table('website_menu_items', function (Blueprint $table) {
            $table->foreign('website_page_id')
                ->references('id')
                ->on('website_pages')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_menu_items', function (Blueprint $table) {
            $table->dropForeign(['website_page_id']);
        });
    }
};

