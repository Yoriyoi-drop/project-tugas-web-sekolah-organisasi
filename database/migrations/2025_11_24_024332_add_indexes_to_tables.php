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
        // Add indexes to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'email')) return;
            $table->index('email');
            $table->index('is_admin');
        });

        // Add indexes to students table
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'nis')) return;
            $table->index('email');
            $table->index('class');
        });

        // Add indexes to teachers table
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'email')) return;
            $table->index('email');
            $table->index('subject');
        });

        // Add indexes to activities table
        Schema::table('activities', function (Blueprint $table) {
            if (!Schema::hasColumn('activities', 'date')) return;
            $table->index('date');
            $table->index('category');
        });

        // Add indexes to posts table
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'category')) return;
            $table->index('category');
            $table->index('is_published');
        });

        // Add indexes to registrations table
        Schema::table('registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('registrations', 'status')) return;
            $table->index('status');
            $table->index('organization_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['is_admin']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['class']);
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['subject']);
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->dropIndex(['date']);
            $table->dropIndex(['category']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropIndex(['is_published']);
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['organization_id']);
        });
    }
};
