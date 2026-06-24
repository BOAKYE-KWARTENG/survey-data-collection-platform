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
        Schema::create('respondent_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('survey_submissions')->cascadeOnDelete();
            $table->string('respondent_id')->unique();
            $table->date('interview_date')->nullable();
            $table->time('interview_start_time')->nullable();
            $table->string('full_name')->nullable();
            $table->string('gender')->nullable();
            $table->integer('age')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('education_level')->nullable();
            $table->string('religion')->nullable();
            $table->boolean('has_disability')->default(false);
            $table->string('disability_type')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('alternative_phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respondent_profiles');
    }
};
