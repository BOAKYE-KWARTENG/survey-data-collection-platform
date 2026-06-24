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
        Schema::create('household_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('survey_submissions')->cascadeOnDelete();
            $table->integer('household_size')->nullable();
            $table->integer('number_of_adults')->nullable();
            $table->integer('number_of_children')->nullable();
            $table->string('household_head_gender')->nullable();
            $table->string('respondent_relationship')->nullable();
            $table->string('residence_type')->nullable();
            $table->string('drinking_water_source')->nullable();
            $table->string('electricity_source')->nullable();
            $table->boolean('has_internet_at_home')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('household_information');
    }
};
