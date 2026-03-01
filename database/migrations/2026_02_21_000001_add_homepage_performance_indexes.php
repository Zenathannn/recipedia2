<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->index(['status', 'is_featured', 'created_at'], 'recipes_status_featured_created_idx');
            $table->index(['status', 'created_at'], 'recipes_status_created_idx');
            $table->index(['status', 'views_count'], 'recipes_status_views_idx');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index(['is_active', 'order'], 'categories_active_order_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index(['role'], 'users_role_idx');
        });
    }

    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropIndex('recipes_status_featured_created_idx');
            $table->dropIndex('recipes_status_created_idx');
            $table->dropIndex('recipes_status_views_idx');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_active_order_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_idx');
        });
    }
};

