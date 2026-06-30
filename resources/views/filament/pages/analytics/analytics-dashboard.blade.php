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

</x-filament-panels::page>