<?php

namespace App\Filament\Pages\Reports;

use App\Models\SurveyCampaign;
use App\Services\Reports\FinancialInclusionReportService;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;


use App\Exports\FinancialInclusionExport;
use App\Services\Reports\ReportExportService;
use Filament\Actions\Action;



class FinancialInclusionReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';
    protected static ?int $navigationSort = 6;

    public static function getNavigationGroup(): ?string
    {
        return 'Reporting Center';
    }

    public ?int $campaign_id = null;

    public function getTitle(): string|Htmlable
    {
        return 'Financial Inclusion Report';
    }

    public function getView(): string
    {
        return 'filament.pages.reports.financial-inclusion-report';
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

    public function getSummary(): array
    {
        return (new FinancialInclusionReportService())
            ->generateSummary($this->campaign_id);
    }

    public function getDistrictBreakdown(): Collection
    {
        return (new FinancialInclusionReportService())
            ->generateByDistrict($this->campaign_id);
    }


    protected function getHeaderActions(): array
{
    return [
        Action::make('exportExcel')
            ->label('Export Excel')
            ->icon('heroicon-o-table-cells')
            ->color('success')
            ->action(function () {
                $url = (new ReportExportService())->exportExcel(
                    $this->getDistrictBreakdown(),
                    FinancialInclusionExport::class,
                    'financial-inclusion-report',
                    'Financial Inclusion Report'
                );
                $this->redirect($url);
            }),

        Action::make('exportCsv')
            ->label('Export CSV')
            ->icon('heroicon-o-document-text')
            ->color('info')
            ->action(function () {
                $url = (new ReportExportService())->exportCsv(
                    $this->getDistrictBreakdown(),
                    FinancialInclusionExport::class,
                    'financial-inclusion-report',
                    'Financial Inclusion Report'
                );
                $this->redirect($url);
            }),

        Action::make('exportPdf')
            ->label('Export PDF')
            ->icon('heroicon-o-document')
            ->color('danger')
            ->action(function () {
                $url = (new ReportExportService())->exportFinancialInclusionPdf(
                    $this->getDistrictBreakdown(),
                    $this->getSummary(),
                    'financial-inclusion-report'
                );
                $this->redirect($url);
            }),
    ];
}
}