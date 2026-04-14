<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>End of Election Reports</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome (For Icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #102864;
            color: white;
        }

        /* Custom Colors */
        .bg-main-panel {
            background-color: #0C3189;
            border: 1px solid rgba(30, 64, 175, 0.3);
        }

        /* Back Button */
        .btn-back {
            background-color: white;
            color: #113285;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: transform 0.2s;
            text-decoration: none;
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
        }
        .btn-back:hover { transform: scale(1.1); color: #113285; }

        /* Export Button */
        .btn-export {
            background-color: #22c508; /* Bright Green */
            color: white;
            font-weight: 600;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }
        .btn-export:hover {
            background-color: #1ea306;
            color: white;
        }

        /* Card Styles */
        .report-card {
            background-color: white;
            border-radius: 16px;
            padding: 20px;
            color: #333;
            height: 100%;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .card-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-title {
            color: #113285;
            font-weight: 800;
            font-size: 1rem;
            text-transform: uppercase;
            margin: 0;
        }

        /* Table Styling within Cards */
        .table-custom {
            width: 100%;
            font-size: 0.85rem;
        }
        .table-custom thead th {
            text-transform: uppercase;
            font-size: 0.75rem;
            color: #111;
            font-weight: 800;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }
        .table-custom tbody td {
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
            font-weight: 500;
            color: #4b5563;
        }
        .text-blue-name {
            color: #113285;
            font-weight: 700;
        }

        /* Specific layout helper */
        .col-header {
            text-transform: uppercase;
            font-weight: 800;
            font-size: 0.8rem;
            margin-top: 15px;
            margin-bottom: 5px;
            color: #000;
        }
    </style>
</head>

<body class="p-3 p-md-4 min-vh-100 d-flex flex-column">

    <!-- HEADER SECTION -->
    <div class="container-xl mb-4 px-0">
        <!-- Back Button and Title Row -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('view.reports-and-analytics') }}" class="btn-back">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="h3 fw-bold m-0 text-white">End of Election Reports</h1>
                    @if(!empty($endOfElection['election']['name']))
                        <p class="small text-white-50 mb-0 mt-1">{{ $endOfElection['election']['name'] }}</p>
                    @endif
                </div>
            </div>

            <!-- Export to PDF -->
            <a href="{{ route('reports.end-of-election-pdf') }}" class="btn-export text-decoration-none">
                <i class="fa-solid fa-download"></i> Export to PDF
            </a>
        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="container-xl bg-main-panel rounded-4 p-4 shadow-lg flex-fill">

        <div class="row g-4">

            <!-- LEFT COLUMN (Winners & Year Level) -->
            <div class="col-lg-6 d-flex flex-column gap-4">

                <!-- Card 1: Winners by Position -->
                <div class="report-card">
                    <div class="card-header-custom">
                        <h5 class="card-title">Winners by Position</h5>
                    </div>

                    <table class="table table-custom table-borderless m-0">
                        <thead>
                            <tr>
                                <th style="width: 28%">Position</th>
                                <th style="width: 38%">Name</th>
                                <th class="text-end">Votes</th>
                                <th class="text-end">Vote share</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($endOfElection['winners'] as $row)
                                <tr>
                                    <td>{{ $row['position'] }}</td>
                                    <td class="text-blue-name">{{ $row['name'] }}</td>
                                    <td class="text-end">{{ number_format($row['votes']) }}</td>
                                    <td class="text-end">{{ number_format($row['percentage'], 1) }}%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No results for the active election yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Card 2: Turnout by Year Level -->
                <div class="report-card">
                    <div class="card-header-custom">
                        <h5 class="card-title">Turnout by Year Level</h5>
                    </div>

                    <table class="table table-custom table-borderless m-0">
                        <thead>
                            <tr>
                                <th style="width: 36%">Year level</th>
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
                                    <td colspan="4" class="text-center text-muted py-4">No year-level data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- RIGHT COLUMN (Final Vote Counts) -->
            <div class="col-lg-6">
                <div class="report-card">
                    <div class="card-header-custom">
                        <h5 class="card-title">Final Vote Counts</h5>
                    </div>

                    @forelse($endOfElection['full_results'] as $position => $candidates)
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2 {{ $loop->first ? '' : 'mt-3' }}">
                            <span class="col-header">{{ strtoupper($position) }}</span>
                            <div class="d-flex gap-4">
                                <span class="col-header text-end" style="width: 50px;">Votes</span>
                                <span class="col-header text-end" style="width: 70px;">Vote share</span>
                            </div>
                        </div>

                        <table class="table table-custom table-borderless mb-4">
                            <tbody>
                                @foreach($candidates as $cand)
                                    <tr>
                                        <td>{{ $cand['name'] }}</td>
                                        <td class="text-end" style="width: 60px;">{{ number_format((int) ($cand['votes'] ?? 0)) }}</td>
                                        <td class="text-end" style="width: 70px;">{{ number_format((float) ($cand['percentage'] ?? 0), 1) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @empty
                        <p class="text-muted text-center py-4 mb-0">No candidate results for the active election yet.</p>
                    @endforelse

                </div>
            </div>

        </div> <!-- End Row -->
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
