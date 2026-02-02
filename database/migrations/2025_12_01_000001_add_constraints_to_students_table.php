<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Add foreign key constraint if users table exists and has user_id
            if (Schema::hasTable('users') && !Schema::hasColumn('students', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            }
            
            // Add indexes for performance only if they don't exist
            if (!Schema::hasIndex('students', 'students_class_index')) {
                $table->index('class', 'students_class_index');
            }
            if (!Schema::hasIndex('students', 'students_nis_email_index')) {
                $table->index(['nis', 'email'], 'students_nis_email_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'user_id')) {
                $table->dropForeign(['user_id']);
            }
            $table->dropIndex('students_class_index');
            $table->dropIndex('students_nis_email_index');
        });
    }
};
