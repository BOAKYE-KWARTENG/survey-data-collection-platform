<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class QaReview extends Model
{
    use LogsActivity;

    protected $fillable = [
        'submission_id',
        'qa_assignment_id',
        'qa_officer_id',
        'decision',
        'comments',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Decision constants
    const APPROVED           = 'approved';
    const REJECTED           = 'rejected';
    const NEEDS_CLARIFICATION = 'needs_clarification';
    

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['decision', 'comments'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "QA Review {$eventName}");
    }



    public function submission(): BelongsTo
    {
        return $this->belongsTo(SurveySubmission::class, 'submission_id');
    }

    public function qaAssignment(): BelongsTo
    {
        return $this->belongsTo(QaAssignment::class, 'qa_assignment_id');
    }

    public function qaOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'qa_officer_id');
    }
}