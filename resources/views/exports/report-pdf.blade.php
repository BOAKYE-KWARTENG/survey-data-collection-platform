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
        h1 {
            font-size: 16px;
            margin-bottom: 4px;
        }
        p.meta {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead th {
            background-color: #1f2937;
            color: white;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        tbody td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        tfoot td {
            padding: 6px 8px;
            font-weight: bold;
            background-color: #f3f4f6;
            border-top: 2px solid #1f2937;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p class="meta">Generated on {{ now()->format('d M Y, h:i A') }}</p>

    <table>
        <thead>
            <tr>
                @foreach ($headings as $heading)
                    <th>{{ $heading }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    @foreach (array_values($row) as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headings) }}" style="text-align:center; color:#9ca3af;">
                        No data available.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>