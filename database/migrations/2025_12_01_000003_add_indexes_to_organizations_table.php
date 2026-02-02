<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            // Add indexes for performance only if they don't exist
            if (!Schema::hasIndex('organizations', 'organizations_is_active_order_index')) {
                $table->index(['is_active', 'order'], 'organizations_is_active_order_index');
            }
            if (!Schema::hasIndex('organizations', 'organizations_type_index')) {
                $table->index('type', 'organizations_type_index');
            }
            if (!Schema::hasIndex('organizations', 'organizations_created_at_is_active_index')) {
                $table->index(['created_at', 'is_active'], 'organizations_created_at_is_active_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropIndex('organizations_is_active_order_index');
            $table->dropIndex('organizations_type_index');
            $table->dropIndex('organizations_created_at_is_active_index');
        });
    }
};
