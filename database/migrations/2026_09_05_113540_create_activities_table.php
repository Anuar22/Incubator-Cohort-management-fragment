<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add role column to users table if not present
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('officer')->after('email'); // 'officer', 'spsm', 'bsj'
            });
        }

        // 2. Activities Tracker Table
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            // Scope & Ownership
            $table->unsignedSmallInteger('year')->index();
            $table->string('cohort')->index();
            $table->foreignId('officer_id')->constrained('users')->cascadeOnDelete();

            // Task Information
            $table->string('code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('completed_on');
            $table->text('deliverable_result')->nullable();

            // Evidence Management
            $table->string('evidence_file_path')->nullable();
            $table->string('evidence_external_link')->nullable();

            // Weekly Reporting
            $table->timestamp('weekly_digest_sent_at')->nullable();

            // QA / Verification State
            $table->enum('status', [
                'pending_dispatch',
                'under_review',
                'verified',
                'returned_for_correction',
                'open_escalated'
            ])->default('under_review')->index();

            // Review Details
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('review_comments')->nullable();
            $table->date('correction_deadline')->nullable();
            $table->date('corrected_on')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};