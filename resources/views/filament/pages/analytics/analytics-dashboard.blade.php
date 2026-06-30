<x-filament-panels::page>

    {{-- Campaign Filter --}}
    <x-filament::section>
        {{ $this->form }}
    </x-filament::section>

    {{-- Regional Coverage Chart --}}
    @php
        $regionalData = $this->getRegionalCoverageData();
    @endphp

    <x-filament::section heading="Regional Coverage">
        <x-analytics.chart
            chartId="regionalCoverageChart"
            type="bar"
            :labels="$regionalData['labels']"
            :datasets="[
                [
                    'label'           => 'Households',
                    'data'            => $regionalData['households'],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.7)',
                    'borderColor'     => 'rgba(59, 130, 246, 1)',
                    'borderWidth'     => 1,
                ],
                [
                    'label'           => 'Approved Submissions',
                    'data'            => $regionalData['submissions'],
                    'backgroundColor' => 'rgba(16, 185, 129, 0.7)',
                    'borderColor'     => 'rgba(16, 185, 129, 1)',
                    'borderWidth'     => 1,
                ],
            ]"
            title="Households vs Approved Submissions per Region"
            height="350px"
        />
    </x-filament::section>

    {{-- Submission Trend Chart --}}
    @php
        $trendData = $this->getSubmissionTrendData();
    @endphp

    <x-filament::section heading="Submission Trend — Last 30 Days">
        <x-analytics.chart
            chartId="submissionTrendChart"
            type="line"
            :labels="$trendData['labels']"
            :datasets="[
                [
                    'label'           => 'Submissions',
                    'data'            => $trendData['counts'],
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                    'borderColor'     => 'rgba(139, 92, 246, 1)',
                    'borderWidth'     => 2,
                    'fill'            => true,
                    'tension'         => 0.4,
                    'pointBackgroundColor' => 'rgba(139, 92, 246, 1)',
                    'pointRadius'     => 3,
                ],
            ]"
            title="Daily Submissions Over Last 30 Days"
            height="300px"
        />
    </x-filament::section>

    {{-- Financial Inclusion Index Chart --}}
    @php
        $fiData = $this->getFinancialInclusionData();
    @endphp

    <x-filament::section heading="Financial Inclusion Index">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Horizontal Bar Chart --}}
            <x-analytics.chart
                chartId="financialInclusionChart"
                type="bar"
                :labels="$fiData['labels']"
                :datasets="[
                    [
                        'label'           => 'Inclusion Rate (%)',
                        'data'            => $fiData['rates'],
                        'backgroundColor' => [
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(245, 158, 11, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(239, 68, 68, 0.7)',
                            'rgba(139, 92, 246, 0.7)',
                        ],
                        'borderColor' => [
                            'rgba(59, 130, 246, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(239, 68, 68, 1)',
                            'rgba(139, 92, 246, 1)',
                        ],
                        'borderWidth' => 1,
                    ],
                ]"
                title="Financial Inclusion Rates (%)"
                height="300px"
            />

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 gap-3">
                @foreach ($fiData['labels'] as $index => $label)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $label }}
                        </span>
                        <div class="flex items-center gap-3">
                            <div class="w-32 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div
                                    class="h-2 rounded-full"
                                    style="width: {{ $fiData['rates'][$index] }}%;
                                    background-color: {{ ['#3b82f6','#f59e0b','#10b981','#ef4444','#8b5cf6'][$index] }};">
                                </div>
                            </div>
                            <span class="text-sm font-bold w-12 text-right">
                                {{ $fiData['rates'][$index] }}%
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </x-filament::section>
    
</x-filament-panels::page>