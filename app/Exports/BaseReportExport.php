<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class BaseReportExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    ShouldAutoSize,
    WithTitle
{
    protected Collection $data;
    protected string $title;

    public function __construct(Collection $data, string $title)
    {
        $this->data  = $data;
        $this->title = $title;
    }

    public function collection(): Collection
    {
        return $this->data->map(fn ($row) => array_values($row));
    }

    public function title(): string
    {
        return $this->title;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => [
                    'fillType'   => 'solid',
                    'startColor' => ['argb' => 'FF1F2937'],
                ],
            ],
        ];
    }
}