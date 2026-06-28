<x-filament-panels::page>

    {{-- Filter Form --}}
    <x-filament::section>
        {{ $this->form }}
    </x-filament::section>

    {{-- Report Table --}}
    <x-filament::section heading="Enumerator Performance">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3">Enumerator</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3 text-center">Households</th>
                        <th class="px-4 py-3 text-center">Submissions</th>
                        <th class="px-4 py-3 text-center">Approved</th>
                        <th class="px-4 py-3 text-center">Rejected</th>
                        <th class="px-4 py-3 text-center">Pending</th>
                        <th class="px-4 py-3 text-center">Approval Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->getReportData() as $row)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3 font-medium">{{ $row['enumerator'] }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $row['email'] }}</td>
                            <td class="px-4 py-3 text-center">{{ $row['households'] }}</td>
                            <td class="px-4 py-3 text-center">{{ $row['submissions'] }}</td>
                            <td class="px-4 py-3 text-center text-green-600 font-semibold">{{ $row['approved'] }}</td>
                            <td class="px-4 py-3 text-center text-red-600 font-semibold">{{ $row['rejected'] }}</td>
                            <td class="px-4 py-3 text-center text-yellow-600 font-semibold">{{ $row['pending'] }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    {{ (float) $row['approval_rate'] >= 80
                                        ? 'bg-green-100 text-green-800'
                                        : ((float) $row['approval_rate'] >= 50
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : 'bg-red-100 text-red-800') }}">
                                    {{ $row['approval_rate'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-400">
                                No data available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if ($this->getReportData()->isNotEmpty())
                    <tfoot class="font-semibold bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <td class="px-4 py-3" colspan="2">Totals</td>
                            <td class="px-4 py-3 text-center">{{ $this->getReportData()->sum('households') }}</td>
                            <td class="px-4 py-3 text-center">{{ $this->getReportData()->sum('submissions') }}</td>
                            <td class="px-4 py-3 text-center text-green-600">{{ $this->getReportData()->sum('approved') }}</td>
                            <td class="px-4 py-3 text-center text-red-600">{{ $this->getReportData()->sum('rejected') }}</td>
                            <td class="px-4 py-3 text-center text-yellow-600">{{ $this->getReportData()->sum('pending') }}</td>
                            <td class="px-4 py-3 text-center">-</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </x-filament::section>

</x-filament-panels::page>