<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('registered_by')->constrained('users')->cascadeOnDelete();
            
            // Registration details
            $table->enum('status', ['registered', 'confirmed', 'attended', 'absent', 'cancelled'])->default('registered');
            $table->text('notes')->nullable();
            $table->json('responses')->nullable(); // Custom form responses
            
            // Attendance
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->text('feedback')->nullable();
            $table->integer('rating')->nullable(); // 1-5 rating
            
            $table->timestamps();
            
            // Unique constraint to prevent duplicate registrations
            $table->unique(['activity_id', 'member_id'], 'unique_activity_registration');
            
            // Indexes
            $table->index(['activity_id', 'status']);
            $table->index(['member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_registrations');
    }
};
