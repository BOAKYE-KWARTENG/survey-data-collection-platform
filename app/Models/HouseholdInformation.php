<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HouseholdInformation extends Model
{
    protected $table = 'household_information';
    protected $fillable = [
        'submission_id',
        'household_size',
        'number_of_adults',
        'number_of_children',
        'household_head_gender',
        'respondent_relationship',
        'residence_type',
        'drinking_water_source',
        'electricity_source',
        'has_internet_at_home',
    ];

    protected $casts = [
        'has_internet_at_home' => 'boolean',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(SurveySubmission::class, 'submission_id');
    }
}