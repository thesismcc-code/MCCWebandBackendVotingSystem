<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #102864; /* Dark blue background */
            color: white;
            min-height: 100vh;
        }

        /* The Main Blue Panel */
        .main-panel {
            background-color: #0C3189;
            border: 2px solid #3b82f6; /* Lighter blue border */
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            position: relative;
        }

        /* Back Button Style */
        .btn-back {
            background-color: white;
            color: #113285;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
            text-decoration: none;
        }
        .btn-back:hover {
            transform: scale(1.1);
            color: #113285;
        }

        /* White Cards */
        .stat-card {
            background-color: white;
            color: black;
            border-radius: 12px;
            padding: 15px;
            height: 100%;
            min-height: 80px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .stat-title {
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            text-align: right;
        }

        /* Report Button */
        .btn-report {
            background-color: #0d6efd; /* Bootstrap Primary Blue */
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
        }
        .btn-report:hover {
            background-color: #0b5ed7;
            color: white;
        }

        /* Progress Bars */
        .progress {
            height: 15px;
            background-color: #e9ecef;
            border-radius: 20px;
        }
        .progress-bar {
            background-color: #1a1a1a; /* Black fill per image */
            border-radius: 20px;
        }

        .section-title {
            color: #0C3189;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        .turnout-grid .label {
            font-size: 0.9rem;
            color: #444;
        }
        .turnout-grid .value {
            font-size: 1.2rem;
            font-weight: 800;
            color: #000;
        }
    </style>
</head>
<body class="p-4">

    <!-- HEADER SECTION -->
    <div class="container-fluid max-w-7xl mb-4">
        <div class="d-flex align-items-center gap-3">
            <!-- Back Button -->
            <a href="{{ route('view.quick-access') }}" class="btn-back shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>

            <!-- Title Text -->
            <div>
                <h1 class="h4 fw-bold mb-0">Reports & Analytics</h1>
                <p class="text-info mb-0" style="font-size: 12px; --bs-text-opacity: .8;">Real-time summary, statistics, and results.</p>
            </div>
        </div>
    </div>

    <!-- MAIN DASHBOARD CONTAINER -->
    <div class="container-fluid">
        <div class="main-panel">

            <!-- Top Right Button -->
            <div class="d-flex justify-content-end mb-4">
                <a href="{{route('view.reports-and-analytics-end-of-election')}}" class="btn btn-report">End of Election Reports</a>
            </div>

            <!-- ROW 1: TOP STATS (5 Columns) -->
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-3 mb-4">
                <!-- Card 1 -->
                <div class="col">
                    <div class="stat-card">
                        <div class="stat-title">Total Registered Voters:</div>
                        <div class="stat-value">150</div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col">
                    <div class="stat-card">
                        <div class="stat-title">Total Votes Cast:</div>
                        <div class="stat-value">150</div>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col">
                    <div class="stat-card">
                        <div class="stat-title">Total Positions:</div>
                        <div class="stat-value">3</div>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="col">
                    <div class="stat-card">
                        <div class="stat-title">Total Candidates:</div>
                        <div class="stat-value">19</div>
                    </div>
                </div>
                <!-- Card 5 -->
                <div class="col">
                    <div class="stat-card">
                        <div class="stat-title">Voter Turnout:</div>
                        <div class="stat-value">80%</div>
                    </div>
                </div>
            </div>

            <!-- ROW 2: DETAILED CHARTS -->
            <div class="row g-4">

                <!-- LEFT: REAL TIME VOTER TURNOUT -->
                <div class="col-md-5">
                    <div class="stat-card p-4">
                        <div class="section-title">REAL TIME VOTER TURNOUT</div>

                        <div class="row turnout-grid g-4">
                            <div class="col-6">
                                <div class="label">Total Voters:</div>
                                <div class="value">100</div>
                            </div>
                            <div class="col-6">
                                <div class="label">Turnout:</div>
                                <div class="value">10%</div>
                            </div>
                            <div class="col-6">
                                <div class="label">Voted:</div>
                                <div class="value">53</div>
                            </div>
                            <div class="col-6">
                                <div class="label">Not Yet:</div>
                                <div class="value">47</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: PER YEAR LEVEL TURNOUT -->
                <div class="col-md-7">
                    <div class="stat-card p-4">
                        <div class="section-title">PER YEAR LEVEL TURNOUT</div>

                        <!-- Chart Items -->
                        <div class="d-flex flex-column gap-3">

                            <!-- 1st Year -->
                            <div class="d-flex align-items-center">
                                <span style="width: 80px; font-weight: 500;">1st Year</span>
                                <div class="progress flex-grow-1 mx-3">
                                    <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span style="width: 40px; text-align: right;">80%</span>
                            </div>

                            <!-- 2nd Year -->
                            <div class="d-flex align-items-center">
                                <span style="width: 80px; font-weight: 500;">2nd Year</span>
                                <div class="progress flex-grow-1 mx-3">
                                    <div class="progress-bar" role="progressbar" style="width: 67%" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span style="width: 40px; text-align: right;">67%</span>
                            </div>

                            <!-- 3rd Year -->
                            <div class="d-flex align-items-center">
                                <span style="width: 80px; font-weight: 500;">3rd Year</span>
                                <div class="progress flex-grow-1 mx-3">
                                    <div class="progress-bar" role="progressbar" style="width: 43%" aria-valuenow="43" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span style="width: 40px; text-align: right;">43%</span>
                            </div>

                            <!-- 4th Year -->
                            <div class="d-flex align-items-center">
                                <span style="width: 80px; font-weight: 500;">4th Year</span>
                                <div class="progress flex-grow-1 mx-3">
                                    <div class="progress-bar" role="progressbar" style="width: 31%" aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span style="width: 40px; text-align: right;">31%</span>
                            </div>

                        </div>
                    </div>
                </div>

            </div> <!-- End Row 2 -->

        </div> <!-- End Main Panel -->
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
