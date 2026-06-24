<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Household extends Model
{
    protected $fillable = [
        'campaign_id',
        'district_id',
        'community_id',
        'household_code',
        'gps_latitude',
        'gps_longitude',
        'registered_by',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(SurveyCampaign::class, 'campaign_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }

    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(SurveySubmission::class);
    }



    public static function generateCode(District $district): string
    {
        $prefix = 'GNHR';
        $districtCode = strtoupper($district->code);
        $count = static::whereHas('district', function ($query) use ($district) {
            $query->where('id', $district->id);
        })->count();

        $sequence = str_pad($count + 1, 6, '0', STR_PAD_LEFT);

        return "{$prefix}-{$districtCode}-{$sequence}";
    }
}