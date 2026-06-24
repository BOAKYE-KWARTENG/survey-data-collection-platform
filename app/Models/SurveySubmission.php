<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SurveySubmission extends Model
{
    protected $fillable = [
        'campaign_id',
        'household_id',
        'enumerator_id',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(SurveyCampaign::class, 'campaign_id');
    }

    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    public function enumerator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'enumerator_id');
    }

    public function respondentProfile(): HasOne
    {
        return $this->hasOne(RespondentProfile::class, 'submission_id');
    }

    public function householdInformation(): HasOne
    {
        return $this->hasOne(HouseholdInformation::class, 'submission_id');
    }

    public function financialInclusion(): HasOne
    {
        return $this->hasOne(FinancialInclusion::class, 'submission_id');
    }

    public function digitalAccess(): HasOne
    {
        return $this->hasOne(DigitalAccess::class, 'submission_id');
    }

    public function employmentInformation(): HasOne
    {
        return $this->hasOne(EmploymentInformation::class, 'submission_id');
    }
}