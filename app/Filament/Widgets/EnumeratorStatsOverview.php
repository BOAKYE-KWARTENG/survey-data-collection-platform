<?php

namespace App\Filament\Widgets;

use App\Models\Household;
use App\Models\SurveySubmission;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EnumeratorStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();

        return [
            Stat::make('My Households',
                Household::where('registered_by', $userId)->count()
            )
                ->description('Households I registered')
                ->icon('heroicon-o-home-modern')
                ->color('primary'),

            Stat::make('My Submissions',
                SurveySubmission::where('enumerator_id', $userId)->count()
            )
                ->description('Total surveys submitted')
                ->icon('heroicon-o-document-text')
                ->color('success'),

            Stat::make('Submitted Today',
                SurveySubmission::where('enumerator_id', $userId)
                    ->whereDate('submitted_at', today())
                    ->count()
            )
                ->description('My submissions today')
                ->icon('heroicon-o-calendar')
                ->color('warning'),

            Stat::make('Draft Submissions',
                SurveySubmission::where('enumerator_id', $userId)
                    ->where('status', 'draft')
                    ->count()
            )
                ->description('Pending submission')
                ->icon('heroicon-o-clock')
                ->color('danger'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole('enumerator');
    }
}