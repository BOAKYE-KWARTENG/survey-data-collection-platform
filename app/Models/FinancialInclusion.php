<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialInclusion extends Model
{
    protected $table = 'financial_inclusion';
    protected $fillable = [
        'submission_id',
        'has_bank_account',
        'bank_institution',
        'bank_account_duration',
        'has_mobile_money',
        'mobile_money_provider',
        'mobile_money_frequency',
        'saves_money',
        'savings_location',
        'borrowed_last_12_months',
        'loan_source',
        'has_insurance',
        'insurance_types',
    ];

    protected $casts = [
        'has_bank_account'        => 'boolean',
        'has_mobile_money'        => 'boolean',
        'saves_money'             => 'boolean',
        'borrowed_last_12_months' => 'boolean',
        'has_insurance'           => 'boolean',
        'savings_location'        => 'array',
        'insurance_types'         => 'array',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(SurveySubmission::class, 'submission_id');
    }
}