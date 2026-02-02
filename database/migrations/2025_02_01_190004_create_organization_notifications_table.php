<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Notification content
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['announcement', 'discussion', 'activity', 'reminder', 'system'])->default('system');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            
            // Related entity
            $table->morphs('notifiable'); // Polymorphic relation to discussion, activity, etc.
            
            // Status
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            
            // Delivery
            $table->enum('channel', ['web', 'email', 'push', 'sms'])->default('web');
            $table->boolean('sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            
            // Metadata
            $table->json('data')->nullable(); // Additional data
            $table->string('action_url')->nullable(); // Link to related content
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'is_read']);
            $table->index(['organization_id', 'type']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_notifications');
    }
};
