<?php

namespace App\Services\Reports;

use App\Models\District;
use App\Models\WorkflowStatus;
use Illuminate\Support\Collection;

class DistrictCoverageReportService
{
    public function generate(?int $campaignId = null): Collection
    {
        $approvedStatus = WorkflowStatus::where('name', 'Approved')->first();
        $rejectedStatus = WorkflowStatus::where('name', 'Rejected')->first();

        return District::query()
            ->with(['region'])
            ->withCount([
                // Total households
                'households' => function ($query) use ($campaignId) {
                    if ($campaignId) {
                        $query->where('campaign_id', $campaignId);
                    }
                },

                // Total submissions
                'households as submissions_count' => function ($query) use ($campaignId) {
                    $query->join(
                        'survey_submissions',
                        'survey_submissions.household_id',
                        '=',
                        'households.id'
                    );
                    if ($campaignId) {
                        $query->where('survey_submissions.campaign_id', $campaignId);
                    }
                },

                // Approved submissions
                'households as approved_count' => function ($query) use ($campaignId, $approvedStatus) {
                    $query->join(
                        'survey_submissions',
                        'survey_submissions.household_id',
                        '=',
                        'households.id'
                    );
                    if ($approvedStatus) {
                        $query->where('survey_submissions.workflow_status_id', $approvedStatus->id);
                    }
                    if ($campaignId) {
                        $query->where('survey_submissions.campaign_id', $campaignId);
                    }
                },

                // Rejected submissions
                'households as rejected_count' => function ($query) use ($campaignId, $rejectedStatus) {
                    $query->join(
                        'survey_submissions',
                        'survey_submissions.household_id',
                        '=',
                        'households.id'
                    );
                    if ($rejectedStatus) {
                        $query->where('survey_submissions.workflow_status_id', $rejectedStatus->id);
                    }
                    if ($campaignId) {
                        $query->where('survey_submissions.campaign_id', $campaignId);
                    }
                },
            ])
            ->get()
            ->map(function ($district) {
                $coveragePercent = $district->households_count > 0
                    ? round(($district->submissions_count / $district->households_count) * 100, 1)
                    : 0;

                $pendingCount = $district->submissions_count
                    - $district->approved_count
                    - $district->rejected_count;

                return [
                    'district'         => $district->name,
                    'region'           => $district->region->name ?? '-',
                    'households'       => $district->households_count,
                    'submissions'      => $district->submissions_count,
                    'approved'         => $district->approved_count,
                    'rejected'         => $district->rejected_count,
                    'pending'          => max(0, $pendingCount),
                    'coverage_percent' => $coveragePercent . '%',
                ];
            });
    }
}