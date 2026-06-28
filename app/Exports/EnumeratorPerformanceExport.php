<?php

namespace App\Exports;

class EnumeratorPerformanceExport extends BaseReportExport
{
    public function headings(): array
    {
        return [
            'Enumerator',
            'Email',
            'Households',
            'Submissions',
            'Approved',
            'Rejected',
            'Pending',
            'Approval Rate',
        ];
    }
}