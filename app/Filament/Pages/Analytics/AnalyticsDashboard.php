<?php

namespace App\Filament\Pages\Analytics;

use App\Models\SurveyCampaign;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use App\Services\Analytics\AnalyticsService;

class AnalyticsDashboard extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?int $navigationSort = 7;

    public static function getNavigationGroup(): ?string
    {
        return 'Analytics';
    }

    public ?int $campaign_id = null;

    public function getTitle(): string|Htmlable
    {
        return 'Analytics Dashboard';
    }

    public function getView(): string
    {
        return 'filament.pages.analytics.analytics-dashboard';
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['admin', 'supervisor']);
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('campaign_id')
                ->label('Filter by Campaign')
                ->options(SurveyCampaign::pluck('name', 'id'))
                ->placeholder('All Campaigns')
                ->nullable()
                ->live(),
        ];
    }


    public function getRegionalCoverageData(): array
    {
        return (new AnalyticsService($this->campaign_id))
            ->regionalCoverage();
    }


    public function getSubmissionTrendData(): array
    {
        return (new AnalyticsService($this->campaign_id))
            ->submissionTrend();
    }


    public function getFinancialInclusionData(): array
    {
        return (new AnalyticsService($this->campaign_id))
            ->financialInclusionIndex();
    }


    public function getEmploymentData(): array
    {
        return (new AnalyticsService($this->campaign_id))
            ->employmentBreakdown();
    }


    public function getIncomeData(): array
    {
        return (new AnalyticsService($this->campaign_id))
            ->incomeDistribution();
    }

    
    public function getGenderData(): array
    {
        return (new AnalyticsService($this->campaign_id))
            ->genderBreakdown();
    }
}