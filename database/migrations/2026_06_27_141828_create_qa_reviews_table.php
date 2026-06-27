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
        Schema::create('qa_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')
                ->constrained('survey_submissions')
                ->cascadeOnDelete();
            $table->foreignId('qa_assignment_id')
                ->constrained('qa_assignments')
                ->cascadeOnDelete();
            $table->foreignId('qa_officer_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('decision');
            $table->text('comments')->nullable();
            $table->timestamp('reviewed_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qa_reviews');
    }
};
