<?php

namespace App\Filament\Pages\Reports;

use App\Exports\QaPerformanceExport;
use App\Services\Reports\QaPerformanceReportService;
use App\Services\Reports\ReportExportService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;

class QaPerformanceReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?int $navigationSort = 6;

    public static function getNavigationGroup(): ?string
    {
        return 'Reporting Center';
    }

    public function getTitle(): string|Htmlable
    {
        return 'QA Performance Report';
    }

    public function getView(): string
    {
        return 'filament.pages.reports.qa-performance-report';
    }

    public function getReportData(): Collection
    {
        return (new QaPerformanceReportService())->generate();
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
                        $this->getReportData(),
                        DistrictCoverageExport::class,
                        'district-coverage-report',
                        'District Coverage Report'
                    );
                    $this->js("window.open('{$url}', '_blank')");
                }),

            Action::make('exportCsv')
                ->label('Export CSV')
                ->icon('heroicon-o-document-text')
                ->color('info')
                ->action(function () {
                    $url = (new ReportExportService())->exportCsv(
                        $this->getReportData(),
                        DistrictCoverageExport::class,
                        'district-coverage-report',
                        'District Coverage Report'
                    );
                    $this->js("window.open('{$url}', '_blank')");
                }),

            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->color('danger')
                ->action(function () {
                    $url = (new ReportExportService())->exportPdf(
                        $this->getReportData(),
                        ['District', 'Region', 'Households', 'Submissions', 'Approved', 'Rejected', 'Pending', 'Coverage'],
                        'district-coverage-report',
                        'District Coverage Report'
                    );
                    $this->js("window.open('{$url}', '_blank')");
                }),
        ];
    }
}