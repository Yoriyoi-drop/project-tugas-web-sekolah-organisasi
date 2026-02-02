<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Organization Analytics Table
        Schema::create('organization_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            
            // Membership metrics
            $table->integer('total_members')->default(0);
            $table->integer('active_members')->default(0);
            $table->integer('new_members')->default(0);
            $table->integer('member_growth_rate')->default(0); // percentage
            
            // Activity metrics
            $table->integer('total_activities')->default(0);
            $table->integer('upcoming_activities')->default(0);
            $table->integer('completed_activities')->default(0);
            $table->integer('total_participants')->default(0);
            $table->decimal('attendance_rate', 5, 2)->default(0); // percentage
            
            // Engagement metrics
            $table->integer('total_discussions')->default(0);
            $table->integer('discussion_replies')->default(0);
            $table->integer('total_announcements')->default(0);
            $table->integer('total_notifications')->default(0);
            $table->integer('read_notifications')->default(0);
            
            // Performance metrics
            $table->decimal('activity_score', 5, 2)->default(0); // 0-100
            $table->decimal('engagement_score', 5, 2)->default(0); // 0-100
            $table->decimal('overall_score', 5, 2)->default(0); // 0-100
            
            $table->timestamps();
            
            // Indexes
            $table->unique(['organization_id', 'date'], 'unique_org_date');
            $table->index(['date']);
            $table->index(['organization_id']);
        });

        // Activity Analytics Table
        Schema::create('activity_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            
            // Registration metrics
            $table->integer('total_registrations')->default(0);
            $table->integer('new_registrations')->default(0);
            $table->integer('confirmed_registrations')->default(0);
            $table->integer('cancelled_registrations')->default(0);
            
            // Engagement metrics
            $table->integer('views')->default(0);
            $table->integer('unique_views')->default(0);
            $table->integer('shares')->default(0);
            $table->integer('likes')->default(0);
            
            // Performance metrics
            $table->decimal('registration_rate', 5, 2)->default(0); // percentage
            $table->decimal('cancellation_rate', 5, 2)->default(0); // percentage
            $table->decimal('engagement_rate', 5, 2)->default(0); // percentage
            
            $table->timestamps();
            
            // Indexes
            $table->unique(['activity_id', 'date'], 'unique_activity_date');
            $table->index(['date']);
            $table->index(['activity_id']);
        });

        // Member Analytics Table
        Schema::create('member_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            
            // Activity participation
            $table->integer('activities_registered')->default(0);
            $table->integer('activities_attended')->default(0);
            $table->integer('activities_completed')->default(0);
            
            // Engagement metrics
            $table->integer('discussions_started')->default(0);
            $table->integer('discussion_replies')->default(0);
            $table->integer('announcements_read')->default(0);
            $table->integer('notifications_read')->default(0);
            
            // Performance metrics
            $table->decimal('attendance_rate', 5, 2)->default(0); // percentage
            $table->decimal('engagement_score', 5, 2)->default(0); // 0-100
            $table->decimal('participation_score', 5, 2)->default(0); // 0-100
            
            $table->timestamps();
            
            // Indexes
            $table->unique(['member_id', 'date'], 'unique_member_date');
            $table->index(['date']);
            $table->index(['member_id']);
        });

        // Reports Table
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            
            // Report details
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['membership', 'activity', 'engagement', 'performance', 'financial', 'custom'])->default('custom');
            $table->enum('status', ['pending', 'generating', 'completed', 'failed'])->default('pending');
            $table->enum('format', ['pdf', 'excel', 'csv', 'json'])->default('pdf');
            
            // Report parameters
            $table->json('filters')->nullable(); // Date range, organization, etc.
            $table->json('parameters')->nullable(); // Report configuration
            
            // File information
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->integer('file_size')->nullable();
            
            // Metadata
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->integer('download_count')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['organization_id', 'type']);
            $table->index(['status']);
            $table->index(['created_at']);
        });

        // Report Templates Table
        Schema::create('report_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['membership', 'activity', 'engagement', 'performance', 'financial', 'custom']);
            
            // Template configuration
            $table->json('sections')->nullable(); // Report sections
            $table->json('fields')->nullable(); // Data fields to include
            $table->json('filters')->nullable(); // Available filters
            $table->json('formatting')->nullable(); // Formatting options
            
            // Template metadata
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['type']);
            $table->index(['is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_templates');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('member_analytics');
        Schema::dropIfExists('activity_analytics');
        Schema::dropIfExists('organization_analytics');
    }
};
