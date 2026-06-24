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
        Schema::create('digital_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('survey_submissions')->cascadeOnDelete();
            $table->boolean('owns_mobile_phone')->default(false);
            $table->string('mobile_phone_type')->nullable();
            $table->boolean('owns_computer')->default(false);
            $table->boolean('used_internet_last_3_months')->default(false);
            $table->string('internet_access_method')->nullable();
            $table->string('internet_frequency')->nullable();
            $table->json('digital_skills')->nullable();
            $table->boolean('used_mobile_banking')->default(false);
            $table->boolean('made_online_payment_last_12_months')->default(false);
            $table->boolean('received_money_digitally')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_accesses');
    }
};
