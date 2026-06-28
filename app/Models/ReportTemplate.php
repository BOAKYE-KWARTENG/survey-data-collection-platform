<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Report type constants
    const DISTRICT_COVERAGE      = 'district_coverage';
    const ENUMERATOR_PERFORMANCE = 'enumerator_performance';
    const QA_PERFORMANCE         = 'qa_performance';
    const FINANCIAL_INCLUSION    = 'financial_inclusion';
    const GENDER_REPORT          = 'gender_report';
    const EMPLOYMENT_REPORT      = 'employment_report';
}