<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->text('address')->nullable();
            $table->string('role')->default('user');
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->json('social_links')->nullable();
            $table->json('skills')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'bio', 'avatar', 'birth_date', 'gender', 
                'address', 'role', 'department', 'position', 
                'social_links', 'skills', 'is_active', 'last_login_at'
            ]);
        });
    }
};