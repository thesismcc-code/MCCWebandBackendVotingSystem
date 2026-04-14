<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1a1a2e;
            padding: 24px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #102864;
            padding-bottom: 12px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: 800;
            color: #102864;
            letter-spacing: 0.5px;
        }

        .header p {
            font-size: 10px;
            color: #666;
            margin-top: 4px;
        }

        .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
            font-size: 10px;
            color: #555;
        }

        h2 {
            font-size: 11px;
            font-weight: 800;
            color: #102864;
            text-transform: uppercase;
            margin: 14px 0 8px 0;
            letter-spacing: 0.5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 12px;
        }

        thead tr {
            background-color: #102864;
            color: white;
        }

        thead th {
            padding: 8px 6px;
            text-align: left;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        tbody tr:nth-child(even) { background-color: #f5f7ff; }
        tbody tr:nth-child(odd)  { background-color: #ffffff; }

        tbody td {
            padding: 6px;
            font-size: 10px;
            border-bottom: 1px solid #e8ecf5;
            color: #333;
        }

        .text-end { text-align: right; }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>End of Election Report</h1>
        <p>Fingerprint Voting System — Official summary</p>
    </div>

    <div class="meta">
        <div>Generated: {{ $generatedAt->format('F d, Y h:i A') }}</div>
        <div>Election: {{ $endOfElection['election']['name'] ?? 'N/A' }}</div>
    </div>

    <h2>Winners by position</h2>
    <table>
        <thead>
            <tr>
                <th>Position</th>
                <th>Name</th>
                <th class="text-end">Votes</th>
                <th class="text-end">Vote share</th>
            </tr>
        </thead>
        <tbody>
            @forelse($endOfElection['winners'] as $row)
                <tr>
                    <td>{{ $row['position'] }}</td>
                    <td>{{ $row['name'] }}</td>
                    <td class="text-end">{{ number_format($row['votes']) }}</td>
                    <td class="text-end">{{ number_format($row['percentage'], 1) }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding: 12px; color: #999;">No results.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Turnout by year level</h2>
    <table>
        <thead>
            <tr>
                <th>Year level</th>
                <th class="text-end">Total</th>
                <th class="text-end">Voted</th>
                <th class="text-end">Turnout</th>
            </tr>
        </thead>
        <tbody>
            @forelse($endOfElection['year_levels'] as $yl)
                <tr>
                    <td>{{ $yl['year_level'] }}</td>
                    <td class="text-end">{{ number_format((int) ($yl['total_students'] ?? 0)) }}</td>
                    <td class="text-end">{{ number_format((int) ($yl['voted'] ?? 0)) }}</td>
                    <td class="text-end">{{ number_format((float) ($yl['turnout_percent'] ?? 0), 1) }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding: 12px; color: #999;">No data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Final vote counts by position</h2>
    @foreach($endOfElection['full_results'] as $position => $candidates)
        <p style="font-weight: 800; font-size: 10px; color: #102864; margin-top: 10px; text-transform: uppercase;">{{ $position }}</p>
        <table>
            <thead>
                <tr>
                    <th>Candidate</th>
                    <th class="text-end">Votes</th>
                    <th class="text-end">Vote share</th>
                </tr>
            </thead>
            <tbody>
                @foreach($candidates as $cand)
                    <tr>
                        <td>{{ $cand['name'] }}</td>
                        <td class="text-end">{{ number_format((int) ($cand['votes'] ?? 0)) }}</td>
                        <td class="text-end">{{ number_format((float) ($cand['percentage'] ?? 0), 1) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    @if(empty($endOfElection['full_results']))
        <p style="text-align:center; color: #999; padding: 12px;">No candidate results.</p>
    @endif

    <div class="footer">
        This document is system-generated for the active election period.
    </div>

</body>
</html>
