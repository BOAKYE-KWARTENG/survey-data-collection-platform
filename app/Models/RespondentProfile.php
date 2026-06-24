<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RespondentProfile extends Model
{
    protected $fillable = [
        'submission_id',
        'respondent_id',
        'interview_date',
        'interview_start_time',
        'full_name',
        'gender',
        'age',
        'date_of_birth',
        'marital_status',
        'education_level',
        'religion',
        'has_disability',
        'disability_type',
        'phone_number',
        'alternative_phone',
    ];

    protected $casts = [
        'interview_date'  => 'date',
        'date_of_birth'   => 'date',
        'has_disability'  => 'boolean',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(SurveySubmission::class, 'submission_id');
    }

    public static function generateRespondentId(): string
    {
        $prefix = 'RES';
        $year   = now()->format('Y');
        $count  = static::count();
        $sequence = str_pad($count + 1, 6, '0', STR_PAD_LEFT);

        return "{$prefix}-{$year}-{$sequence}";
    }
}