<?php

namespace App\Filament\Widgets;

use App\Models\EnumeratorDeployment;
use App\Models\Household;
use App\Models\SurveySubmission;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SupervisorStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Households', Household::count())
                ->description('Registered across all districts')
                ->icon('heroicon-o-home-modern')
                ->color('primary'),

            Stat::make('Total Submissions', SurveySubmission::count())
                ->description('All survey submissions')
                ->icon('heroicon-o-document-text')
                ->color('success'),

            Stat::make('Submissions Today',
                SurveySubmission::whereDate('submitted_at', today())->count()
            )
                ->description('Submitted today')
                ->icon('heroicon-o-calendar')
                ->color('warning'),

            Stat::make('Enumerators Deployed', EnumeratorDeployment::where('status', 'active')->count())
                ->description('Active deployments')
                ->icon('heroicon-o-users')
                ->color('info'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole(['admin', 'supervisor']);
    }
}