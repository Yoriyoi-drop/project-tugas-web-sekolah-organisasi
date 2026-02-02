<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained()->cascadeOnDelete();
            
            // Membership details
            $table->enum('status', ['active', 'inactive', 'alumni', 'suspended'])->default('active');
            $table->enum('role', ['member', 'secretary', 'treasurer', 'vice_leader', 'leader'])->default('member');
            $table->string('position')->nullable(); // Custom position like "Koordinator Bidang Keagamaan"
            
            // Period management
            $table->string('period')->nullable(); // e.g., "2024/2025"
            $table->date('join_date')->default(now());
            $table->date('end_date')->nullable(); // For alumni or inactive members
            
            // Additional info
            $table->text('notes')->nullable();
            $table->json('achievements')->nullable(); // Store achievements as JSON
            $table->json('skills')->nullable(); // Store relevant skills
            
            $table->timestamps();
            
            // Indexes
            $table->index(['organization_id', 'status']);
            $table->index(['student_id', 'status']);
            $table->index(['organization_id', 'role']);
            $table->index('period');
            
            // Unique constraint to prevent duplicate memberships
            $table->unique(['organization_id', 'student_id', 'period'], 'unique_membership_period');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
