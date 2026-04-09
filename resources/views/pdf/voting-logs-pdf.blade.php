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
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
            border-bottom: 2px solid #102864;
            padding-bottom: 16px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: 800;
            color: #102864;
            letter-spacing: 1px;
        }

        .header p {
            font-size: 11px;
            color: #666;
            margin-top: 4px;
        }

        .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            font-size: 10px;
            color: #555;
        }

        .filters-applied {
            background: #f0f4ff;
            border: 1px solid #c7d4f7;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 16px;
            font-size: 10px;
            color: #333;
        }

        .filters-applied span {
            font-weight: 700;
            color: #102864;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        thead tr {
            background-color: #102864;
            color: white;
        }

        thead th {
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        tbody tr:nth-child(even) { background-color: #f5f7ff; }
        tbody tr:nth-child(odd)  { background-color: #ffffff; }

        tbody td {
            padding: 8px;
            font-size: 10px;
            border-bottom: 1px solid #e8ecf5;
            color: #333;
        }

        .status-badge {
            background-color: #dcfce7;
            color: #166534;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
        }

        .footer {
            margin-top: 24px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 12px;
        }

        .summary {
            margin-bottom: 16px;
            display: flex;
            gap: 12px;
        }

        .summary-box {
            background: #102864;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-align: center;
        }

        .summary-box .num  { font-size: 18px; font-weight: 800; }
        .summary-box .label { font-size: 9px; opacity: 0.8; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>Voting Logs Report</h1>
        <p>Fingerprint Voting System — Official Record</p>
    </div>

    <!-- Meta -->
    <div class="meta">
        <div>Generated: {{ \Carbon\Carbon::now()->format('F d, Y h:i A') }}</div>
        <div>Election: {{ $electionName }}</div>
    </div>

    <!-- Active Filters -->
    @if($search || $course || $yearLevel)
    <div class="filters-applied">
        <span>Filters applied:</span>
        @if($search)    &nbsp; Search: <span>{{ $search }}</span> @endif
        @if($course)    &nbsp; Course: <span>{{ $course }}</span> @endif
        @if($yearLevel) &nbsp; Year Level: <span>{{ $yearLevel }}</span> @endif
    </div>
    @endif

    <!-- Summary -->
    <div class="summary">
        <div class="summary-box">
            <div class="num">{{ count($logs) }}</div>
            <div class="label">Total Voters</div>
        </div>
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Course</th>
                <th>Year Level</th>
                <th>Date & Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $log['student_id'] }}</td>
                    <td>{{ $log['name'] }}</td>
                    <td>{{ $log['course'] }}</td>
                    <td>{{ $log['year_level'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($log['voted_at'])->format('m-d-Y g:iA') }}</td>
                    <td><span class="status-badge">{{ $log['status'] }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding: 20px; color: #999;">
                        No voting logs found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        This document is system-generated. Total records: {{ count($logs) }}
    </div>

</body>
</html>
