<?php

namespace App\Services\Reports;

use App\Models\FinancialInclusion;
use App\Models\District;
use App\Models\WorkflowStatus;
use Illuminate\Support\Collection;

class FinancialInclusionReportService
{
    public function generateSummary(?int $campaignId = null): array
    {
        $query = FinancialInclusion::query()
            ->whereHas('submission', function ($q) use ($campaignId) {
                $approvedStatus = WorkflowStatus::where('name', 'Approved')->first();
                if ($approvedStatus) {
                    $q->where('workflow_status_id', $approvedStatus->id);
                }
                if ($campaignId) {
                    $q->where('campaign_id', $campaignId);
                }
            });

        $total = $query->count();

        if ($total === 0) {
            return $this->emptySummary();
        }

        return [
            'total_responses'          => $total,
            'bank_account_count'       => (clone $query)->where('has_bank_account', true)->count(),
            'bank_account_rate'        => $this->rate((clone $query)->where('has_bank_account', true)->count(), $total),
            'mobile_money_count'       => (clone $query)->where('has_mobile_money', true)->count(),
            'mobile_money_rate'        => $this->rate((clone $query)->where('has_mobile_money', true)->count(), $total),
            'saves_money_count'        => (clone $query)->where('saves_money', true)->count(),
            'saves_money_rate'         => $this->rate((clone $query)->where('saves_money', true)->count(), $total),
            'borrowed_count'           => (clone $query)->where('borrowed_last_12_months', true)->count(),
            'borrowed_rate'            => $this->rate((clone $query)->where('borrowed_last_12_months', true)->count(), $total),
            'insurance_count'          => (clone $query)->where('has_insurance', true)->count(),
            'insurance_rate'           => $this->rate((clone $query)->where('has_insurance', true)->count(), $total),
            'mobile_money_providers'   => $this->mobileMoneyProviderBreakdown(clone $query),
            'savings_locations'        => $this->savingsLocationBreakdown(clone $query),
            'loan_sources'             => $this->loanSourceBreakdown(clone $query),
        ];
    }

    public function generateByDistrict(?int $campaignId = null): Collection
    {
        $approvedStatus = WorkflowStatus::where('name', 'Approved')->first();

        return District::query()
            ->with('region')
            ->get()
            ->map(function ($district) use ($campaignId, $approvedStatus) {
                $query = FinancialInclusion::query()
                    ->whereHas('submission', function ($q) use ($district, $campaignId, $approvedStatus) {
                        $q->whereHas('household', function ($hq) use ($district) {
                            $hq->where('district_id', $district->id);
                        });
                        if ($approvedStatus) {
                            $q->where('workflow_status_id', $approvedStatus->id);
                        }
                        if ($campaignId) {
                            $q->where('campaign_id', $campaignId);
                        }
                    });

                $total = $query->count();

                return [
                    'district'          => $district->name,
                    'region'            => $district->region->name ?? '-',
                    'total'             => $total,
                    'bank_account_rate' => $total > 0
                        ? $this->rate((clone $query)->where('has_bank_account', true)->count(), $total)
                        : '0%',
                    'mobile_money_rate' => $total > 0
                        ? $this->rate((clone $query)->where('has_mobile_money', true)->count(), $total)
                        : '0%',
                    'savings_rate'      => $total > 0
                        ? $this->rate((clone $query)->where('saves_money', true)->count(), $total)
                        : '0%',
                    'insurance_rate'    => $total > 0
                        ? $this->rate((clone $query)->where('has_insurance', true)->count(), $total)
                        : '0%',
                ];
            })
            ->filter(fn ($row) => $row['total'] > 0)
            ->values();
    }

    private function mobileMoneyProviderBreakdown($query): array
    {
        return $query->where('has_mobile_money', true)
            ->selectRaw('mobile_money_provider, count(*) as count')
            ->groupBy('mobile_money_provider')
            ->pluck('count', 'mobile_money_provider')
            ->toArray();
    }

    private function savingsLocationBreakdown($query): array
    {
        $results = [];
        $records = $query->where('saves_money', true)
            ->whereNotNull('savings_location')
            ->pluck('savings_location');

        foreach ($records as $locations) {
            if (is_array($locations)) {
                foreach ($locations as $location) {
                    $results[$location] = ($results[$location] ?? 0) + 1;
                }
            }
        }

        arsort($results);
        return $results;
    }

    private function loanSourceBreakdown($query): array
    {
        return $query->where('borrowed_last_12_months', true)
            ->whereNotNull('loan_source')
            ->selectRaw('loan_source, count(*) as count')
            ->groupBy('loan_source')
            ->pluck('count', 'loan_source')
            ->toArray();
    }

    private function rate(int $count, int $total): string
    {
        return round(($count / $total) * 100, 1) . '%';
    }

    private function emptySummary(): array
    {
        return [
            'total_responses'        => 0,
            'bank_account_count'     => 0,
            'bank_account_rate'      => '0%',
            'mobile_money_count'     => 0,
            'mobile_money_rate'      => '0%',
            'saves_money_count'      => 0,
            'saves_money_rate'       => '0%',
            'borrowed_count'         => 0,
            'borrowed_rate'          => '0%',
            'insurance_count'        => 0,
            'insurance_rate'         => '0%',
            'mobile_money_providers' => [],
            'savings_locations'      => [],
            'loan_sources'           => [],
        ];
    }
}