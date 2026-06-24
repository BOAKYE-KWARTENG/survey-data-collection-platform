<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigitalAccess extends Model
{
    protected $table = 'digital_access';
    protected $fillable = [
        'submission_id',
        'owns_mobile_phone',
        'mobile_phone_type',
        'owns_computer',
        'used_internet_last_3_months',
        'internet_access_method',
        'internet_frequency',
        'digital_skills',
        'used_mobile_banking',
        'made_online_payment_last_12_months',
        'received_money_digitally',
    ];

    protected $casts = [
        'owns_mobile_phone'                  => 'boolean',
        'owns_computer'                      => 'boolean',
        'used_internet_last_3_months'        => 'boolean',
        'used_mobile_banking'                => 'boolean',
        'made_online_payment_last_12_months' => 'boolean',
        'received_money_digitally'           => 'boolean',
        'digital_skills'                     => 'array',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(SurveySubmission::class, 'submission_id');
    }
}