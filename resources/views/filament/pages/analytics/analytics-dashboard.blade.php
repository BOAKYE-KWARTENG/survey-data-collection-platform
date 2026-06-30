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


    {{-- Employment Statistics --}}
    @php
        $employmentData = $this->getEmploymentData();
        $incomeData     = $this->getIncomeData();
    @endphp

    <x-filament::section heading="Employment & Income Statistics">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Employment Status Doughnut --}}
            <div>
                <x-analytics.chart
                    chartId="employmentStatusChart"
                    type="doughnut"
                    :labels="$employmentData['labels']"
                    :datasets="[
                        [
                            'label'           => 'Employment Status',
                            'data'            => $employmentData['counts'],
                            'backgroundColor' => [
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(139, 92, 246, 0.8)',
                            ],
                            'borderColor' => '#ffffff',
                            'borderWidth' => 2,
                        ],
                    ]"
                    title="Employment Status Distribution"
                    height="300px"
                />

                {{-- Legend --}}
                <div class="mt-4 grid grid-cols-2 gap-2">
                    @foreach ($employmentData['labels'] as $index => $label)
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full inline-block flex-shrink-0"
                                style="background-color: {{ ['#3b82f6','#10b981','#ef4444','#f59e0b','#8b5cf6'][$index % 5] }}">
                            </span>
                            <span class="text-xs text-gray-600 dark:text-gray-400">
                                {{ $label }}:
                                <strong>{{ $employmentData['counts'][$index] }}</strong>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Income Distribution Bar --}}
            <div>
                <x-analytics.chart
                    chartId="incomeDistributionChart"
                    type="bar"
                    :labels="$incomeData['labels']"
                    :datasets="[
                        [
                            'label'           => 'Number of Respondents',
                            'data'            => $incomeData['counts'],
                            'backgroundColor' => 'rgba(16, 185, 129, 0.7)',
                            'borderColor'     => 'rgba(16, 185, 129, 1)',
                            'borderWidth'     => 1,
                        ],
                    ]"
                    title="Monthly Income Distribution"
                    height="300px"
                />
            </div>

        </div>
    </x-filament::section>
    

    {{-- Gender Breakdown --}}
    @php
        $genderData  = $this->getGenderData();
        $totalGender = array_sum($genderData['counts']);
    @endphp

    <x-filament::section heading="Gender Breakdown">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Doughnut Chart --}}
            <x-analytics.chart
                chartId="genderBreakdownChart"
                type="doughnut"
                :labels="$genderData['labels']"
                :datasets="[
                    [
                        'label'           => 'Gender',
                        'data'            => $genderData['counts'],
                        'backgroundColor' => [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(244, 114, 182, 0.8)',
                            'rgba(156, 163, 175, 0.8)',
                        ],
                        'borderColor' => '#ffffff',
                        'borderWidth' => 2,
                    ],
                ]"
                title="Gender Distribution"
                height="300px"
            />

            {{-- Summary Cards --}}
            <div class="flex flex-col justify-center gap-4">

                @foreach ($genderData['labels'] as $index => $label)
                    @php
                        $count      = $genderData['counts'][$index];
                        $percentage = $totalGender > 0
                            ? round(($count / $totalGender) * 100, 1)
                            : 0;
                        $colors = [
                            'bg-blue-50 border-blue-200 dark:bg-blue-900/20 dark:border-blue-800',
                            'bg-pink-50 border-pink-200 dark:bg-pink-900/20 dark:border-pink-800',
                            'bg-gray-50 border-gray-200 dark:bg-gray-800 dark:border-gray-700',
                        ];
                        $textColors = [
                            'text-blue-600',
                            'text-pink-500',
                            'text-gray-500',
                        ];
                    @endphp

                    <div class="flex items-center justify-between p-4 rounded-lg border {{ $colors[$index % 3] }}">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</p>
                            <p class="text-2xl font-bold {{ $textColors[$index % 3] }}">
                                {{ number_format($count) }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold {{ $textColors[$index % 3] }}">
                                {{ $percentage }}%
                            </p>
                            <p class="text-xs text-gray-400">of total</p>
                        </div>
                    </div>

                @endforeach

                {{-- Total --}}
                <div class="flex items-center justify-between p-4 rounded-lg border bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Respondents</p>
                        <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">
                            {{ number_format($totalGender) }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-gray-700 dark:text-gray-200">100%</p>
                        <p class="text-xs text-gray-400">all genders</p>
                    </div>
                </div>

            </div>

        </div>
    </x-filament::section>
</x-filament-panels::page>