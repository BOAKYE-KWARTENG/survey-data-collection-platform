<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Database\Eloquent\Relations\HasMany;


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

    /* Update SurveySubmission to Track Status Properly */
    public function transitionTo(WorkflowStatus $status): void
    {
        $this->update([
            'workflow_status_id' => $status->id,
            'status'             => strtolower($status->name),
        ]);
    }


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

    public function workflowStatus(): BelongsTo
    {
        return $this->belongsTo(WorkflowStatus::class);
    }
    
    public function qaAssignments(): HasMany
    {
        return $this->hasMany(QaAssignment::class, 'submission_id');
    }

    public function latestQaAssignment(): HasOne
    {
        return $this->hasOne(QaAssignment::class, 'submission_id')
            ->latestOfMany();
    }

    public function qaReviews(): HasMany
    {
        return $this->hasMany(QaReview::class, 'submission_id');
    }

    public function latestQaReview(): HasOne
    {
        return $this->hasOne(QaReview::class, 'submission_id')
            ->latestOfMany();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(SubmissionComment::class, 'submission_id');
    }

}