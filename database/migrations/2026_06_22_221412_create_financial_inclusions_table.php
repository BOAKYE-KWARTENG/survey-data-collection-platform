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
        Schema::create('financial_inclusion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('survey_submissions')->cascadeOnDelete();
            $table->boolean('has_bank_account')->default(false);
            $table->string('bank_institution')->nullable();
            $table->string('bank_account_duration')->nullable();
            $table->boolean('has_mobile_money')->default(false);
            $table->string('mobile_money_provider')->nullable();
            $table->string('mobile_money_frequency')->nullable();
            $table->boolean('saves_money')->default(false);
            $table->json('savings_location')->nullable();
            $table->boolean('borrowed_last_12_months')->default(false);
            $table->string('loan_source')->nullable();
            $table->boolean('has_insurance')->default(false);
            $table->json('insurance_types')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_inclusions');
    }
};
