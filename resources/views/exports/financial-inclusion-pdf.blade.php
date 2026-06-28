<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
        }
        h1 { font-size: 16px; margin-bottom: 4px; }
        h2 { font-size: 13px; margin-top: 16px; margin-bottom: 6px; }
        p.meta { font-size: 10px; color: #6b7280; margin-bottom: 16px; }
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 16px;
        }
        .summary-card {
            display: table-cell;
            width: 20%;
            border: 1px solid #e5e7eb;
            padding: 8px;
            text-align: center;
        }
        .summary-label { font-size: 9px; color: #6b7280; text-transform: uppercase; }
        .summary-value { font-size: 18px; font-weight: bold; color: #1f2937; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            background-color: #1f2937;
            color: white;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        tbody tr:nth-child(even) { background-color: #f9fafb; }
        tbody td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <h1>Financial Inclusion Report</h1>
    <p class="meta">Generated on {{ now()->format('d M Y, h:i A') }}</p>

    <h2>Summary Metrics</h2>
    <div class="summary-grid">
        <div class="summary-card">
            <p class="summary-label">Total Responses</p>
            <p class="summary-value">{{ $summary['total_responses'] }}</p>
        </div>
        <div class="summary-card">
            <p class="summary-label">Bank Account</p>
            <p class="summary-value">{{ $summary['bank_account_rate'] }}</p>
        </div>
        <div class="summary-card">
            <p class="summary-label">Mobile Money</p>
            <p class="summary-value">{{ $summary['mobile_money_rate'] }}</p>
        </div>
        <div class="summary-card">
            <p class="summary-label">Saves Money</p>
            <p class="summary-value">{{ $summary['saves_money_rate'] }}</p>
        </div>
        <div class="summary-card">
            <p class="summary-label">Has Insurance</p>
            <p class="summary-value">{{ $summary['insurance_rate'] }}</p>
        </div>
    </div>

    <h2>District Breakdown</h2>
    <table>
        <thead>
            <tr>
                <th>District</th>
                <th>Region</th>
                <th>Responses</th>
                <th>Bank Account</th>
                <th>Mobile Money</th>
                <th>Saves Money</th>
                <th>Insurance</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row['district'] }}</td>
                    <td>{{ $row['region'] }}</td>
                    <td>{{ $row['total'] }}</td>
                    <td>{{ $row['bank_account_rate'] }}</td>
                    <td>{{ $row['mobile_money_rate'] }}</td>
                    <td>{{ $row['savings_rate'] }}</td>
                    <td>{{ $row['insurance_rate'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center; color:#9ca3af;">
                        No data available.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>