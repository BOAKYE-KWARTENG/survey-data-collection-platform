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
        Schema::create('employment_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('survey_submissions')->cascadeOnDelete();
            $table->string('employment_status')->nullable();
            $table->string('main_occupation')->nullable();
            $table->string('employment_sector')->nullable();
            $table->boolean('owns_business')->default(false);
            $table->boolean('business_registered')->nullable();
            $table->integer('number_of_employees')->nullable();
            $table->string('main_income_source')->nullable();
            $table->string('monthly_income_range')->nullable();
            $table->string('household_monthly_income_range')->nullable();
            $table->boolean('can_meet_emergency_expense')->default(false);
            $table->string('financial_confidence')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_information');
    }
};
