@props([
    'chartId',
    'type' => 'bar',
    'labels' => [],
    'datasets' => [],
    'title' => '',
    'height' => '300px',
])

<div>
    @if ($title)
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ $title }}</h3>
    @endif
    <div style="height: {{ $height }}; position: relative;">
        <canvas id="{{ $chartId }}"></canvas>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('{{ $chartId }}');
        if (!ctx) return;

        new Chart(ctx, {
            type: '{{ $type }}',
            data: {
                labels: @json($labels),
                datasets: @json($datasets),
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                },
                scales: {
                    @if (in_array($type, ['bar', 'line']))
                    y: {
                        beginAtZero: true,
                    },
                    @endif
                },
            },
        });
    });

    // Re-render when Livewire updates
    document.addEventListener('livewire:navigated', function () {
        const ctx = document.getElementById('{{ $chartId }}');
        if (!ctx) return;

        if (Chart.getChart(ctx)) {
            Chart.getChart(ctx).destroy();
        }

        new Chart(ctx, {
            type: '{{ $type }}',
            data: {
                labels: @json($labels),
                datasets: @json($datasets),
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                },
                scales: {
                    @if (in_array($type, ['bar', 'line']))
                    y: {
                        beginAtZero: true,
                    },
                    @endif
                },
            },
        });
    });
</script>
@endpush