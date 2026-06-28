<?php

namespace App\Services\Reports;

use App\Models\QaReview;
use App\Models\User;
use Illuminate\Support\Collection;

class QaPerformanceReportService
{
    public function generate(?int $campaignId = null): Collection
    {
        return User::role('qa_officer')
            ->withCount([
                // Total assignments
                'qaAssignments',

                // Total reviews completed
                'qaReviews',

                // Approved
                'qaReviews as approved_count' => function ($query) {
                    $query->where('decision', QaReview::APPROVED);
                },

                // Rejected
                'qaReviews as rejected_count' => function ($query) {
                    $query->where('decision', QaReview::REJECTED);
                },

                // Needs Clarification
                'qaReviews as clarification_count' => function ($query) {
                    $query->where('decision', QaReview::NEEDS_CLARIFICATION);
                },
            ])
            ->get()
            ->map(function ($officer) {
                $approvalRate = $officer->qa_reviews_count > 0
                    ? round(($officer->approved_count / $officer->qa_reviews_count) * 100, 1)
                    : 0;

                $pendingCount = $officer->qa_assignments_count
                    - $officer->qa_reviews_count;

                return [
                    'qa_officer'      => $officer->name,
                    'email'           => $officer->email,
                    'assignments'     => $officer->qa_assignments_count,
                    'completed'       => $officer->qa_reviews_count,
                    'approved'        => $officer->approved_count,
                    'rejected'        => $officer->rejected_count,
                    'clarification'   => $officer->clarification_count,
                    'pending'         => max(0, $pendingCount),
                    'approval_rate'   => $approvalRate . '%',
                ];
            });
    }
}