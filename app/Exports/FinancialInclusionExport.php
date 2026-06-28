<?php

namespace App\Exports;

class FinancialInclusionExport extends BaseReportExport
{
    public function headings(): array
    {
        return [
            'District',
            'Region',
            'Responses',
            'Bank Account Rate',
            'Mobile Money Rate',
            'Saves Money Rate',
            'Insurance Rate',
        ];
    }
}