<?php

namespace App\Filament\Pages\Reports;

use App\Models\SurveyCampaign;
use App\Services\Reports\DistrictCoverageReportService;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use BackedEnum;


use App\Exports\DistrictCoverageExport;
use App\Services\Reports\ReportExportService;
use Filament\Actions\Action;




class DistrictCoverageReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map';
    protected static ?int $navigationSort = 6;
    public function getView(): string
    {
        return 'filament.pages.reports.district-coverage-report';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Reporting Center';
    }

    public ?int $campaign_id = null;

    public function getTitle(): string|Htmlable
    {
        return 'District Coverage Report';
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

    #[Computed]
    public function getReportData(): Collection
    {
        return (new DistrictCoverageReportService())
            ->generate($this->campaign_id);
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
                    $this->redirect($url);
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
                    $this->redirect($url);
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
                    $this->redirect($url);
                }),
        ];
    }

}