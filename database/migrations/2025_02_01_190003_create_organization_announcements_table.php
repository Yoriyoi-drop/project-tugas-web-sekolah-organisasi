<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            
            // Announcement content
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['general', 'urgent', 'meeting', 'event', 'achievement', 'reminder'])->default('general');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            
            // Display settings
            $table->boolean('is_active')->default(true);
            $table->boolean('is_pinned')->default(false);
            $table->timestamp('published_at')->default(now());
            $table->timestamp('expires_at')->nullable();
            
            // Targeting
            $table->json('target_roles')->nullable(); // Specific roles to target
            $table->json('target_members')->nullable(); // Specific member IDs
            
            // Engagement
            $table->integer('view_count')->default(0);
            $table->integer('read_count')->default(0);
            
            // Media
            $table->string('attachment')->nullable();
            $table->string('attachment_type')->nullable(); // pdf, image, link
            
            $table->timestamps();
            
            // Indexes
            $table->index(['organization_id', 'is_active', 'published_at']);
            $table->index(['organization_id', 'priority', 'is_pinned']);
            $table->index(['expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_announcements');
    }
};
