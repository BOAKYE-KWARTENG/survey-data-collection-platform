<?php

namespace App\Filament\Widgets;

use App\Models\QaAssignment;
use App\Models\QaReview;
use App\Models\SurveySubmission;
use App\Models\WorkflowStatus;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QaOfficerStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();

        $pendingAssignments = QaAssignment::where('qa_officer_id', $userId)
            ->where('status', 'pending')
            ->count();

        $completedReviews = QaReview::where('qa_officer_id', $userId)
            ->count();

        $approvedCount = QaReview::where('qa_officer_id', $userId)
            ->where('decision', QaReview::APPROVED)
            ->count();

        $rejectedCount = QaReview::where('qa_officer_id', $userId)
            ->where('decision', QaReview::REJECTED)
            ->count();

        $approvalRate = $completedReviews > 0
            ? round(($approvedCount / $completedReviews) * 100, 1)
            : 0;

        return [
            Stat::make('Pending Reviews', $pendingAssignments)
                ->description('Submissions in my basket')
                ->icon('heroicon-o-inbox')
                ->color('warning'),

            Stat::make('Completed Reviews', $completedReviews)
                ->description('Total reviews submitted')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('info'),

            Stat::make('Approved', $approvedCount)
                ->description('Submissions approved')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Rejected', $rejectedCount)
                ->description('Submissions rejected')
                ->icon('heroicon-o-x-circle')
                ->color('danger'),

            Stat::make('Approval Rate', $approvalRate . '%')
                ->description('Approved vs total reviewed')
                ->icon('heroicon-o-chart-bar')
                ->color('primary'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole('qa_officer');
    }
}