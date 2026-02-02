<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add unique constraints for security only if they don't exist
            if (!Schema::hasIndex('users', 'users_email_unique')) {
                $table->unique('email', 'users_email_unique');
            }
            if (!Schema::hasIndex('users', 'users_nik_unique')) {
                $table->unique('nik', 'users_nik_unique');
            }
            if (!Schema::hasIndex('users', 'users_nis_unique')) {
                $table->unique('nis', 'users_nis_unique');
            }
            
            // Add indexes for performance only if they don't exist
            if (!Schema::hasIndex('users', 'users_is_admin_is_active_index')) {
                $table->index(['is_admin', 'is_active'], 'users_is_admin_is_active_index');
            }
            if (!Schema::hasIndex('users', 'users_email_verified_at_index')) {
                $table->index('email_verified_at', 'users_email_verified_at_index');
            }
            if (!Schema::hasIndex('users', 'users_last_login_at_index')) {
                $table->index('last_login_at', 'users_last_login_at_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_unique');
            $table->dropUnique('users_nik_unique');
            $table->dropUnique('users_nis_unique');
            $table->dropIndex('users_is_admin_is_active_index');
            $table->dropIndex('users_email_verified_at_index');
            $table->dropIndex('users_last_login_at_index');
        });
    }
};
