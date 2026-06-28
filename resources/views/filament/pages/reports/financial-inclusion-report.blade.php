<x-filament-panels::page>

    {{-- Filter --}}
    <x-filament::section>
        {{ $this->form }}
    </x-filament::section>

    @php $summary = $this->getSummary(); @endphp

    {{-- Summary Cards --}}
    <x-filament::section heading="Summary Metrics">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">

            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border dark:border-gray-700 text-center">
                <p class="text-xs text-gray-500 uppercase">Total Responses</p>
                <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $summary['total_responses'] }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border dark:border-gray-700 text-center">
                <p class="text-xs text-gray-500 uppercase">Bank Account</p>
                <p class="text-3xl font-bold text-blue-600 mt-1">{{ $summary['bank_account_rate'] }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $summary['bank_account_count'] }} respondents</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border dark:border-gray-700 text-center">
                <p class="text-xs text-gray-500 uppercase">Mobile Money</p>
                <p class="text-3xl font-bold text-yellow-500 mt-1">{{ $summary['mobile_money_rate'] }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $summary['mobile_money_count'] }} respondents</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border dark:border-gray-700 text-center">
                <p class="text-xs text-gray-500 uppercase">Saves Money</p>
                <p class="text-3xl font-bold text-green-600 mt-1">{{ $summary['saves_money_rate'] }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $summary['saves_money_count'] }} respondents</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border dark:border-gray-700 text-center">
                <p class="text-xs text-gray-500 uppercase">Has Insurance</p>
                <p class="text-3xl font-bold text-purple-600 mt-1">{{ $summary['insurance_rate'] }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $summary['insurance_count'] }} respondents</p>
            </div>

        </div>
    </x-filament::section>

    {{-- Breakdowns --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- Mobile Money Providers --}}
        <x-filament::section heading="Mobile Money Providers">
            @forelse ($summary['mobile_money_providers'] as $provider => $count)
                <div class="flex justify-between items-center py-2 border-b dark:border-gray-700">
                    <span class="text-sm capitalize">{{ str_replace('_', ' ', $provider) }}</span>
                    <span class="font-semibold text-yellow-600">{{ $count }}</span>
                </div>
            @empty
                <p class="text-gray-400 text-sm">No data available.</p>
            @endforelse
        </x-filament::section>

        {{-- Savings Locations --}}
        <x-filament::section heading="Where People Save">
            @forelse ($summary['savings_locations'] as $location => $count)
                <div class="flex justify-between items-center py-2 border-b dark:border-gray-700">
                    <span class="text-sm capitalize">{{ str_replace('_', ' ', $location) }}</span>
                    <span class="font-semibold text-green-600">{{ $count }}</span>
                </div>
            @empty
                <p class="text-gray-400 text-sm">No data available.</p>
            @endforelse
        </x-filament::section>

        {{-- Loan Sources --}}
        <x-filament::section heading="Loan Sources">
            @forelse ($summary['loan_sources'] as $source => $count)
                <div class="flex justify-between items-center py-2 border-b dark:border-gray-700">
                    <span class="text-sm capitalize">{{ str_replace('_', ' ', $source) }}</span>
                    <span class="font-semibold text-red-600">{{ $count }}</span>
                </div>
            @empty
                <p class="text-gray-400 text-sm">No data available.</p>
            @endforelse
        </x-filament::section>

    </div>

    {{-- District Breakdown --}}
    <x-filament::section heading="Financial Inclusion by District">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3">District</th>
                        <th class="px-4 py-3">Region</th>
                        <th class="px-4 py-3 text-center">Responses</th>
                        <th class="px-4 py-3 text-center">Bank Account</th>
                        <th class="px-4 py-3 text-center">Mobile Money</th>
                        <th class="px-4 py-3 text-center">Saves Money</th>
                        <th class="px-4 py-3 text-center">Insurance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->getDistrictBreakdown() as $row)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3 font-medium">{{ $row['district'] }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $row['region'] }}</td>
                            <td class="px-4 py-3 text-center">{{ $row['total'] }}</td>
                            <td class="px-4 py-3 text-center text-blue-600 font-semibold">{{ $row['bank_account_rate'] }}</td>
                            <td class="px-4 py-3 text-center text-yellow-600 font-semibold">{{ $row['mobile_money_rate'] }}</td>
                            <td class="px-4 py-3 text-center text-green-600 font-semibold">{{ $row['savings_rate'] }}</td>
                            <td class="px-4 py-3 text-center text-purple-600 font-semibold">{{ $row['insurance_rate'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-400">
                                No approved submissions available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>

</x-filament-panels::page>