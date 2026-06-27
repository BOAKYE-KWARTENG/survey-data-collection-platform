<?php

namespace App\Filament\Widgets;

use App\Models\EnumeratorDeployment;
use App\Models\Household;
use App\Models\QaAssignment;
use App\Models\SurveySubmission;
use App\Models\WorkflowStatus;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SupervisorStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $pendingQaStatus = WorkflowStatus::where('name', 'Submitted')->first();
        $rejectedStatus  = WorkflowStatus::where('name', 'Rejected')->first();
        $approvedStatus  = WorkflowStatus::where('name', 'Approved')->first();

        return [
            Stat::make(
                'Total Households',
                Household::count()
            )
                ->description('Registered across all districts')
                ->icon('heroicon-o-home-modern')
                ->color('primary'),

            Stat::make(
                'Total Submissions',
                SurveySubmission::count()
            )
                ->description('All survey submissions')
                ->icon('heroicon-o-document-text')
                ->color('info'),

            Stat::make(
                'Pending QA Assignment',
                $pendingQaStatus
                    ? SurveySubmission::where('workflow_status_id', $pendingQaStatus->id)->count()
                    : 0
            )
                ->description('Submitted — awaiting QA assignment')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make(
                'Rejected Submissions',
                $rejectedStatus
                    ? SurveySubmission::where('workflow_status_id', $rejectedStatus->id)->count()
                    : 0
            )
                ->description('Sent back to enumerators')
                ->icon('heroicon-o-x-circle')
                ->color('danger'),

            Stat::make(
                'Approved Submissions',
                $approvedStatus
                    ? SurveySubmission::where('workflow_status_id', $approvedStatus->id)->count()
                    : 0
            )
                ->description('Verified and approved')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make(
                'Enumerators Deployed',
                EnumeratorDeployment::where('status', 'active')->count()
            )
                ->description('Active deployments')
                ->icon('heroicon-o-users')
                ->color('primary'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole(['admin', 'supervisor']);
    }
}