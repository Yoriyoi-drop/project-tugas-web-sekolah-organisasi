<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            
            // Period details
            $table->string('period_name'); // e.g., "Periode 2024/2025"
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false);
            
            // Leadership structure for this period
            $table->json('leadership_structure')->nullable(); // Store leadership positions and members
            
            // Period metadata
            $table->text('description')->nullable();
            $table->integer('member_count')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['organization_id', 'is_active']);
            $table->index(['organization_id', 'start_date']);
            
            // Ensure only one active period per organization
            $table->unique(['organization_id', 'is_active'], 'unique_active_period');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_periods');
    }
};
