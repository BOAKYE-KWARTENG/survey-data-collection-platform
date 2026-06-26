<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowStatus extends Model
{
    protected $fillable = [
        'name',
        'color',
        'sort_order',
        'is_default',
        'is_final',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_final'   => 'boolean',
    ];

    public function submissions(): HasMany
    {
        return $this->hasMany(SurveySubmission::class);
    }
}