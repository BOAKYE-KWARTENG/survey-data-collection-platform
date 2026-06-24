<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyCampaign extends Model
{
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function enumeratorDeployments(): HasMany
    {
        return $this->hasMany(EnumeratorDeployment::class, 'campaign_id');
    }

    public function households(): HasMany
    {
        return $this->hasMany(Household::class, 'campaign_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(SurveySubmission::class, 'campaign_id');
    }
}