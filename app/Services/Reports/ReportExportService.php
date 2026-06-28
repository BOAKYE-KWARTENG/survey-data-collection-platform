<?php

namespace App\Services\Reports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportExportService
{
    public function exportExcel(
        Collection $data,
        string $exportClass,
        string $filename,
        string $title
    ): string {
        $path = 'exports/' . $filename . '-' . now()->format('Ymd-His') . '.xlsx';
        Excel::store(new $exportClass($data, $title), $path, 'public');
        return asset('storage/' . $path);
    }

    public function exportCsv(
        Collection $data,
        string $exportClass,
        string $filename,
        string $title
    ): string {
        $path = 'exports/' . $filename . '-' . now()->format('Ymd-His') . '.csv';
        Excel::store(
            new $exportClass($data, $title),
            $path,
            'public',
            \Maatwebsite\Excel\Excel::CSV
        );
        return asset('storage/' . $path);
    }

    public function exportPdf(
        Collection $data,
        array $headings,
        string $filename,
        string $title
    ): string {
        $path = storage_path('app/public/exports/' . $filename . '-' . now()->format('Ymd-His') . '.pdf');

        Pdf::loadView('exports.report-pdf', [
            'title'    => $title,
            'headings' => $headings,
            'rows'     => $data,
        ])
        ->setPaper('a4', 'landscape')
        ->save($path);

        return asset('storage/exports/' . basename($path));
    }


    public function exportFinancialInclusionPdf(
        Collection $data,
        array $summary,
        string $filename
    ): string {
        $path = storage_path('app/public/exports/' . $filename . '-' . now()->format('Ymd-His') . '.pdf');

        Pdf::loadView('exports.financial-inclusion-pdf', [
            'summary' => $summary,
            'rows'    => $data,
        ])
        ->setPaper('a4', 'landscape')
        ->save($path);

        return asset('storage/exports/' . basename($path));
    }
}