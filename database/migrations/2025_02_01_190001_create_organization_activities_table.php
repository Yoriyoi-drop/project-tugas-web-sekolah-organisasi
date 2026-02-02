<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('coordinator_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Activity details
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['meeting', 'event', 'training', 'competition', 'social', 'religious', 'other'])->default('event');
            $table->enum('status', ['planning', 'upcoming', 'ongoing', 'completed', 'cancelled'])->default('planning');
            
            // Schedule
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->string('location')->nullable();
            $table->boolean('is_online')->default(false);
            $table->string('online_link')->nullable();
            
            // Participation
            $table->integer('max_participants')->nullable();
            $table->integer('registered_count')->default(0);
            $table->boolean('registration_required')->default(true);
            $table->datetime('registration_deadline')->nullable();
            
            // Resources
            $table->json('requirements')->nullable(); // Items to bring
            $table->json('outcomes')->nullable(); // Expected results
            $table->decimal('budget')->nullable(); // Budget estimation
            
            // Media
            $table->string('cover_image')->nullable();
            $table->json('gallery_images')->nullable();
            
            // Engagement
            $table->integer('view_count')->default(0);
            $table->boolean('is_featured')->default(false);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['organization_id', 'status', 'start_datetime']);
            $table->index(['organization_id', 'type']);
            $table->index(['status', 'start_datetime']);
            $table->index(['is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_activities');
    }
};
