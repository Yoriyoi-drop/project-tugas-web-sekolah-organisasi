<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('period_id')->nullable()->constrained('organization_periods')->nullOnDelete();
            $table->date('date');
            
            // Membership performance
            $table->decimal('member_retention_rate', 5, 2)->default(0); // percentage
            $table->decimal('member_acquisition_rate', 5, 2)->default(0); // percentage
            $table->decimal('member_satisfaction_score', 5, 2)->default(0); // 1-5 average
            
            // Activity performance
            $table->integer('total_activities_completed')->default(0);
            $table->decimal('activity_completion_rate', 5, 2)->default(0); // percentage
            $table->decimal('average_participation_rate', 5, 2)->default(0); // percentage
            $table->decimal('activity_satisfaction_score', 5, 2)->default(0); // 1-5 average
            
            // Financial performance (if applicable)
            $table->decimal('budget_utilization', 5, 2)->default(0); // percentage
            $table->decimal('cost_per_member', 8, 2)->default(0);
            $table->decimal('roi_score', 5, 2)->default(0); // return on investment
            
            // Engagement performance
            $table->decimal('discussion_engagement_rate', 5, 2)->default(0); // percentage
            $table->decimal('announcement_read_rate', 5, 2)->default(0); // percentage
            $table->decimal('notification_effectiveness', 5, 2)->default(0); // percentage
            
            // Overall performance
            $table->decimal('overall_performance_score', 5, 2)->default(0); // 0-100
            $table->enum('performance_grade', ['A', 'B', 'C', 'D', 'F'])->nullable();
            
            // Trends and comparisons
            $table->decimal('growth_rate', 5, 2)->default(0); // month over month
            $table->decimal('benchmark_score', 5, 2)->default(0); // vs similar organizations
            
            $table->timestamps();
            
            // Indexes
            $table->unique(['organization_id', 'date'], 'unique_org_perf_date');
            $table->index(['date']);
            $table->index(['organization_id']);
            $table->index(['period_id']);
        });

        // Benchmark Data Table
        Schema::create('benchmark_data', function (Blueprint $table) {
            $table->id();
            $table->string('organization_type'); // OSIS, IPNU, etc.
            $table->string('metric_name'); // member_retention_rate, etc.
            $table->decimal('benchmark_value', 8, 2);
            $table->decimal('percentile_25', 8, 2);
            $table->decimal('percentile_50', 8, 2); // median
            $table->decimal('percentile_75', 8, 2);
            $table->decimal('percentile_90', 8, 2);
            $table->integer('sample_size')->default(0);
            $table->date('benchmark_date');
            
            $table->timestamps();
            
            // Indexes
            $table->unique(['organization_type', 'metric_name', 'benchmark_date'], 'unique_benchmark');
            $table->index(['organization_type']);
            $table->index(['metric_name']);
            $table->index(['benchmark_date']);
        });

        // KPI Targets Table
        Schema::create('kpi_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('period_id')->nullable()->constrained('organization_periods')->nullOnDelete();
            
            // Target details
            $table->string('kpi_name');
            $table->text('description')->nullable();
            $table->enum('category', ['membership', 'activity', 'engagement', 'financial', 'performance']);
            $table->decimal('target_value', 8, 2);
            $table->decimal('current_value', 8, 2)->default(0);
            $table->string('unit')->default('%'); // %, count, score, etc.
            
            // Target status
            $table->enum('status', ['not_started', 'in_progress', 'achieved', 'missed'])->default('not_started');
            $table->decimal('achievement_percentage', 5, 2)->default(0);
            
            // Metadata
            $table->foreignId('set_by')->constrained('users')->cascadeOnDelete();
            $table->date('target_start_date');
            $table->date('target_end_date');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['organization_id', 'period_id']);
            $table->index(['category']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpi_targets');
        Schema::dropIfExists('benchmark_data');
        Schema::dropIfExists('performance_metrics');
    }
};
