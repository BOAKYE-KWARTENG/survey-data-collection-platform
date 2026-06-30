<?php

namespace App\Services\Analytics;

use App\Models\DigitalAccess;
use App\Models\District;
use App\Models\EmploymentInformation;
use App\Models\FinancialInclusion;
use App\Models\Region;
use App\Models\RespondentProfile;
use App\Models\SurveySubmission;
use App\Models\WorkflowStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    private ?int $campaignId;
    private ?int $approvedStatusId;

    public function __construct(?int $campaignId = null)
    {
        $this->campaignId      = $campaignId;
        $this->approvedStatusId = WorkflowStatus::where('name', 'Approved')->first()?->id;
    }

    // Base query for approved submissions
    private function approvedSubmissions()
    {
        $query = SurveySubmission::query();

        if ($this->approvedStatusId) {
            $query->where('workflow_status_id', $this->approvedStatusId);
        }

        if ($this->campaignId) {
            $query->where('campaign_id', $this->campaignId);
        }

        return $query;
    }

    // Regional Coverage
    public function regionalCoverage(): array
    {
        $regions = Region::withCount([
            'districts as households_count' => function ($q) {
                $q->join('households', 'households.district_id', '=', 'districts.id');
                if ($this->campaignId) {
                    $q->where('households.campaign_id', $this->campaignId);
                }
            },
            'districts as submissions_count' => function ($q) {
                $q->join('households', 'households.district_id', '=', 'districts.id')
                  ->join('survey_submissions', 'survey_submissions.household_id', '=', 'households.id');
                if ($this->approvedStatusId) {
                    $q->where('survey_submissions.workflow_status_id', $this->approvedStatusId);
                }
                if ($this->campaignId) {
                    $q->where('survey_submissions.campaign_id', $this->campaignId);
                }
            },
        ])->get();

        return [
            'labels' => $regions->pluck('name')->toArray(),
            'households' => $regions->pluck('households_count')->toArray(),
            'submissions' => $regions->pluck('submissions_count')->toArray(),
        ];
    }

    // Submission Trend (last 30 days)
    public function submissionTrend(): array
    {
        $days = collect(range(29, 0))->map(function ($daysAgo) {
            return now()->subDays($daysAgo)->format('Y-m-d');
        });

        $counts = SurveySubmission::query()
            ->when($this->campaignId, fn($q) => $q->where('campaign_id', $this->campaignId))
            ->whereNotNull('submitted_at')
            ->where('submitted_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(submitted_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        return [
            'labels' => $days->map(fn($d) => date('d M', strtotime($d)))->toArray(),
            'counts' => $days->map(fn($d) => $counts[$d] ?? 0)->toArray(),
        ];
    }

    // Financial Inclusion Index
    public function financialInclusionIndex(): array
    {
        $submissionIds = $this->approvedSubmissions()->pluck('id');
        $total         = $submissionIds->count();

        if ($total === 0) {
            return [
                'labels' => ['Bank Account', 'Mobile Money', 'Saves Money', 'Borrowed', 'Insurance'],
                'rates'  => [0, 0, 0, 0, 0],
            ];
        }

        $fi = FinancialInclusion::whereIn('submission_id', $submissionIds);

        return [
            'labels' => ['Bank Account', 'Mobile Money', 'Saves Money', 'Borrowed', 'Insurance'],
            'rates'  => [
                round((clone $fi)->where('has_bank_account', true)->count() / $total * 100, 1),
                round((clone $fi)->where('has_mobile_money', true)->count() / $total * 100, 1),
                round((clone $fi)->where('saves_money', true)->count() / $total * 100, 1),
                round((clone $fi)->where('borrowed_last_12_months', true)->count() / $total * 100, 1),
                round((clone $fi)->where('has_insurance', true)->count() / $total * 100, 1),
            ],
        ];
    }

    // Employment Status Breakdown
    public function employmentBreakdown(): array
    {
        $submissionIds = $this->approvedSubmissions()->pluck('id');

        $data = EmploymentInformation::whereIn('submission_id', $submissionIds)
            ->selectRaw('employment_status, COUNT(*) as count')
            ->groupBy('employment_status')
            ->pluck('count', 'employment_status');

        return [
            'labels' => $data->keys()->map(fn($k) => ucfirst(str_replace('_', ' ', $k)))->toArray(),
            'counts' => $data->values()->toArray(),
        ];
    }

    // Gender Breakdown
    public function genderBreakdown(): array
    {
        $submissionIds = $this->approvedSubmissions()->pluck('id');

        $data = RespondentProfile::whereIn('submission_id', $submissionIds)
            ->selectRaw('gender, COUNT(*) as count')
            ->groupBy('gender')
            ->pluck('count', 'gender');

        return [
            'labels' => $data->keys()->map(fn($k) => ucfirst($k))->toArray(),
            'counts' => $data->values()->toArray(),
        ];
    }

    // Digital Access Breakdown
    public function digitalAccessBreakdown(): array
    {
        $submissionIds = $this->approvedSubmissions()->pluck('id');
        $total         = $submissionIds->count();

        if ($total === 0) {
            return [
                'labels' => ['Mobile Phone', 'Smartphone', 'Computer', 'Internet', 'Mobile Banking'],
                'rates'  => [0, 0, 0, 0, 0],
            ];
        }

        $da = DigitalAccess::whereIn('submission_id', $submissionIds);

        return [
            'labels' => ['Mobile Phone', 'Smartphone', 'Computer', 'Internet', 'Mobile Banking'],
            'rates'  => [
                round((clone $da)->where('owns_mobile_phone', true)->count() / $total * 100, 1),
                round((clone $da)->where('mobile_phone_type', 'smartphone')->count() / $total * 100, 1),
                round((clone $da)->where('owns_computer', true)->count() / $total * 100, 1),
                round((clone $da)->where('used_internet_last_3_months', true)->count() / $total * 100, 1),
                round((clone $da)->where('used_mobile_banking', true)->count() / $total * 100, 1),
            ],
        ];
    }

    // Income Distribution
    public function incomeDistribution(): array
    {
        $submissionIds = $this->approvedSubmissions()->pluck('id');

        $data = EmploymentInformation::whereIn('submission_id', $submissionIds)
            ->whereNotNull('monthly_income_range')
            ->selectRaw('monthly_income_range, COUNT(*) as count')
            ->groupBy('monthly_income_range')
            ->pluck('count', 'monthly_income_range');

        $labels = [
            'less_than_500' => 'Less than GHS 500',
            '500_to_999'    => 'GHS 500 – 999',
            '1000_to_1999'  => 'GHS 1,000 – 1,999',
            '2000_to_4999'  => 'GHS 2,000 – 4,999',
            '5000_plus'     => 'GHS 5,000+',
        ];

        return [
            'labels' => collect($labels)->values()->toArray(),
            'counts' => collect($labels)->keys()->map(fn($k) => $data[$k] ?? 0)->toArray(),
        ];
    }


    public function digitalSkillsBreakdown(): array
    {
        $submissionIds = $this->approvedSubmissions()->pluck('id');

        $results = [];
        $records = DigitalAccess::whereIn('submission_id', $submissionIds)
            ->whereNotNull('digital_skills')
            ->pluck('digital_skills');

        foreach ($records as $skills) {
            if (is_array($skills)) {
                foreach ($skills as $skill) {
                    $results[$skill] = ($results[$skill] ?? 0) + 1;
                }
            }
        }

        arsort($results);

        $labels = [
            'send_sms'        => 'Send SMS',
            'use_whatsapp'    => 'Use WhatsApp',
            'send_email'      => 'Send Email',
            'download_apps'   => 'Download Apps',
            'online_payments' => 'Make Online Payments',
        ];

        $mapped = [];
        foreach ($results as $key => $count) {
            $mapped[$labels[$key] ?? $key] = $count;
        }

        return $mapped;
    }
}