<?php

namespace App\Filament\Widgets;

use App\Models\Household;
use App\Models\SurveySubmission;
use App\Models\WorkflowStatus;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EnumeratorStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $userId          = auth()->id();
        $rejectedStatus  = WorkflowStatus::where('name', 'Rejected')->first();
        $approvedStatus  = WorkflowStatus::where('name', 'Approved')->first();
        $submittedStatus = WorkflowStatus::where('name', 'Submitted')->first();

        return [
            Stat::make(
                'My Households',
                Household::where('registered_by', $userId)->count()
            )
                ->description('Households I registered')
                ->icon('heroicon-o-home-modern')
                ->color('primary'),

            Stat::make(
                'My Submissions',
                SurveySubmission::where('enumerator_id', $userId)->count()
            )
                ->description('Total surveys submitted')
                ->icon('heroicon-o-document-text')
                ->color('info'),

            Stat::make(
                'Pending Review',
                $submittedStatus
                    ? SurveySubmission::where('enumerator_id', $userId)
                        ->where('workflow_status_id', $submittedStatus->id)
                        ->count()
                    : 0
            )
                ->description('Awaiting QA review')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make(
                'Rejected — Action Required',
                $rejectedStatus
                    ? SurveySubmission::where('enumerator_id', $userId)
                        ->where('workflow_status_id', $rejectedStatus->id)
                        ->count()
                    : 0
            )
                ->description('Needs correction and resubmission')
                ->icon('heroicon-o-x-circle')
                ->color('danger'),

            Stat::make(
                'Approved',
                $approvedStatus
                    ? SurveySubmission::where('enumerator_id', $userId)
                        ->where('workflow_status_id', $approvedStatus->id)
                        ->count()
                    : 0
            )
                ->description('Successfully verified')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole('enumerator');
    }
}