<?php

namespace App\Services\Reports;

use App\Models\User;
use App\Models\QaReview;
use App\Models\WorkflowStatus;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

class EnumeratorPerformanceReportService
{
    public function generate(?int $campaignId = null): Collection
    {
        $approvedStatus = WorkflowStatus::where('name', 'Approved')->first();
        $rejectedStatus = WorkflowStatus::where('name', 'Rejected')->first();

        return User::role('enumerator')
            ->withCount([
                // Total households registered
                'households' => function ($query) use ($campaignId) {
                    if ($campaignId) {
                        $query->where('campaign_id', $campaignId);
                    }
                },

                // Total submissions
                'submissions' => function ($query) use ($campaignId) {
                    if ($campaignId) {
                        $query->where('campaign_id', $campaignId);
                    }
                },

                // Approved submissions
                'submissions as approved_count' => function ($query) use ($campaignId, $approvedStatus) {
                    if ($approvedStatus) {
                        $query->where('workflow_status_id', $approvedStatus->id);
                    }
                    if ($campaignId) {
                        $query->where('campaign_id', $campaignId);
                    }
                },

                // Rejected submissions
                'submissions as rejected_count' => function ($query) use ($campaignId, $rejectedStatus) {
                    if ($rejectedStatus) {
                        $query->where('workflow_status_id', $rejectedStatus->id);
                    }
                    if ($campaignId) {
                        $query->where('campaign_id', $campaignId);
                    }
                },
            ])
            ->get()
            ->map(function ($enumerator) {
                $pendingCount = $enumerator->submissions_count
                    - $enumerator->approved_count
                    - $enumerator->rejected_count;

                $approvalRate = $enumerator->submissions_count > 0
                    ? round(($enumerator->approved_count / $enumerator->submissions_count) * 100, 1)
                    : 0;

                return [
                    'enumerator'    => $enumerator->name,
                    'email'         => $enumerator->email,
                    'households'    => $enumerator->households_count,
                    'submissions'   => $enumerator->submissions_count,
                    'approved'      => $enumerator->approved_count,
                    'rejected'      => $enumerator->rejected_count,
                    'pending'       => max(0, $pendingCount),
                    'approval_rate' => $approvalRate . '%',
                ];
            });
    }
}