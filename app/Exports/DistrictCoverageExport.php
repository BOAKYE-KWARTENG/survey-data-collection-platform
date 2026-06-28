<?php

namespace App\Exports;

class DistrictCoverageExport extends BaseReportExport
{
    public function headings(): array
    {
        return [
            'District',
            'Region',
            'Households',
            'Submissions',
            'Approved',
            'Rejected',
            'Pending',
            'Coverage %',
        ];
    }
}