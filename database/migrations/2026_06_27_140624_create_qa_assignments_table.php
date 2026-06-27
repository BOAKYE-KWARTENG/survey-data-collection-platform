<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('qa_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')
                ->constrained('survey_submissions')
                ->cascadeOnDelete();
            $table->foreignId('qa_officer_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('assigned_by')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->timestamp('assigned_at')->useCurrent();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qa_assignments');
    }
};
