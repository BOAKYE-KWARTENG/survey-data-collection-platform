<?php

namespace App\Exports;

class QaPerformanceExport extends BaseReportExport
{
    public function headings(): array
    {
        return [
            'QA Officer',
            'Email',
            'Assignments',
            'Completed',
            'Approved',
            'Rejected',
            'Clarification',
            'Pending',
            'Approval Rate',
        ];
    }
}