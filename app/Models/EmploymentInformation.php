<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploymentInformation extends Model
{
    protected $table = 'employment_information';
    protected $fillable = [
        'submission_id',
        'employment_status',
        'main_occupation',
        'employment_sector',
        'owns_business',
        'business_registered',
        'number_of_employees',
        'main_income_source',
        'monthly_income_range',
        'household_monthly_income_range',
        'can_meet_emergency_expense',
        'financial_confidence',
    ];

    protected $casts = [
        'owns_business'              => 'boolean',
        'business_registered'        => 'boolean',
        'can_meet_emergency_expense' => 'boolean',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(SurveySubmission::class, 'submission_id');
    }
}