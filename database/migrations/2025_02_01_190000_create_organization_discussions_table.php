<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_discussions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('organization_discussions')->cascadeOnDelete();
            
            // Discussion content
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['discussion', 'announcement', 'question', 'poll'])->default('discussion');
            $table->enum('status', ['active', 'locked', 'archived'])->default('active');
            $table->boolean('is_pinned')->default(false);
            
            // Engagement metrics
            $table->integer('views')->default(0);
            $table->integer('reply_count')->default(0);
            $table->integer('like_count')->default(0);
            
            // Metadata
            $table->json('tags')->nullable();
            $table->timestamp('last_reply_at')->nullable();
            $table->foreignId('last_reply_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['organization_id', 'status', 'is_pinned']);
            $table->index(['organization_id', 'type']);
            $table->index(['author_id']);
            $table->index(['last_reply_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_discussions');
    }
};
