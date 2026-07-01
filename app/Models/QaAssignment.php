<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


class QaAssignment extends Model
{
    
    protected $fillable = [
        'submission_id',
        'qa_officer_id',
        'assigned_by',
        'assigned_at',
        'status',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['submission_id', 'qa_officer_id', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "QA Assignment {$eventName}");
    }


    public function submission(): BelongsTo
    {
        return $this->belongsTo(SurveySubmission::class, 'submission_id');
    }

    public function qaOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'qa_officer_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function review(): HasOne
    {
        return $this->hasOne(QaReview::class, 'qa_assignment_id');
    }
}