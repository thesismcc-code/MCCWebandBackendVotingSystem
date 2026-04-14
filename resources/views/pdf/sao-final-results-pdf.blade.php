<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SAO Final Results</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        h1 { margin: 0 0 6px 0; font-size: 20px; }
        .meta { margin-bottom: 16px; color: #4b5563; }
        .position { margin-bottom: 16px; border: 1px solid #d1d5db; border-radius: 6px; }
        .position-header { background: #e5edff; padding: 8px 10px; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; }
        th { text-align: left; background: #f9fafb; }
        .right { text-align: right; }
        .winners { background: #dcfce7; padding: 8px 10px; }
        .winner-row { margin: 2px 0; }
    </style>
</head>
<body>
    <h1>SAO Final Results</h1>
    <div class="meta">
        <div><strong>Election:</strong> {{ $finalResults['election']['name'] ?? 'N/A' }}</div>
        <div><strong>Status:</strong> {{ $finalResults['publish']['is_published'] ? 'Published' : 'Not Published' }}</div>
        <div><strong>Generated:</strong> {{ $generatedAt->format('F d, Y h:i A') }}</div>
    </div>

    @forelse ($finalResults['sections'] as $section)
        <div class="position">
            <div class="position-header">
                {{ $section['position'] }} (Seats: {{ $section['max_votes'] }})
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th class="right">Votes</th>
                        <th class="right">Vote Share</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($section['candidates'] as $candidate)
                        <tr>
                            <td>{{ $candidate['name'] }}</td>
                            <td class="right">{{ number_format((int) $candidate['votes']) }}</td>
                            <td class="right">{{ number_format((float) $candidate['percentage'], 1) }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="winners">
                <strong>Winner{{ $section['max_votes'] > 1 ? 's' : '' }}:</strong>
                @foreach ($section['winners'] as $winner)
                    <div class="winner-row">{{ $winner['name'] }} - {{ number_format((int) $winner['votes']) }} votes</div>
                @endforeach
            </div>
        </div>
    @empty
        <p>No candidate results available.</p>
    @endforelse
</body>
</html>
