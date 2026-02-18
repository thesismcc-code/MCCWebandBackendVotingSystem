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
            display: flex;
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

        /* View Badge */
        .btn-view {
            background-color: #dbeafe;
            color: #1e40af;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
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
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('view.dashboard') }}" class="btn-back">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="h3 fw-bold m-0 text-white">End of Election Reports</h1>
            </div>

            <!-- Export Button (Top Right) -->
            <button class="btn-export">
                <i class="fa-solid fa-download"></i> Export to PDF
            </button>
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
                        <button class="btn-view"><i class="fa-regular fa-eye"></i> View</button>
                    </div>

                    <table class="table table-custom table-borderless m-0">
                        <thead>
                            <tr>
                                <th style="width: 30%">Position</th>
                                <th style="width: 40%">Name</th>
                                <th class="text-end">Votes</th>
                                <th class="text-end">Turnout</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>President</td>
                                <td class="text-blue-name">Honey Malang</td>
                                <td class="text-end">834</td>
                                <td class="text-end">85%</td>
                            </tr>
                            <tr>
                                <td>Vice President</td>
                                <td class="text-blue-name">Myles Macrohon</td>
                                <td class="text-end">522</td>
                                <td class="text-end">73%</td>
                            </tr>
                            <tr>
                                <td>Secretary</td>
                                <td class="text-blue-name">Jahaira Ampaso</td>
                                <td class="text-end">220</td>
                                <td class="text-end">67%</td>
                            </tr>
                            <tr>
                                <td>Auditor</td>
                                <td class="text-blue-name">Jose Perolino</td>
                                <td class="text-end">200</td>
                                <td class="text-end">66%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Card 2: Turnout by Year Level -->
                <div class="report-card">
                    <div class="card-header-custom">
                        <h5 class="card-title">Turnout by Year Level</h5>
                        <button class="btn-view"><i class="fa-regular fa-eye"></i> View</button>
                    </div>

                    <table class="table table-custom table-borderless m-0">
                        <thead>
                            <tr>
                                <th style="width: 40%">Year Level</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Votes</th>
                                <th class="text-end">Turnout</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1st Year</td>
                                <td class="text-end">834</td>
                                <td class="text-end">834</td>
                                <td class="text-end">85%</td>
                            </tr>
                            <tr>
                                <td>2nd Year</td>
                                <td class="text-end">522</td>
                                <td class="text-end">522</td>
                                <td class="text-end">73%</td>
                            </tr>
                            <tr>
                                <td>3rd Year</td>
                                <td class="text-end">220</td>
                                <td class="text-end">220</td>
                                <td class="text-end">67%</td>
                            </tr>
                            <tr>
                                <td>4th Year</td>
                                <td class="text-end">200</td>
                                <td class="text-end">200</td>
                                <td class="text-end">66%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- RIGHT COLUMN (Final Vote Counts) -->
            <div class="col-lg-6">
                <div class="report-card">
                    <div class="card-header-custom">
                        <h5 class="card-title">Final Vote Counts</h5>
                        <button class="btn-view"><i class="fa-regular fa-eye"></i> View</button>
                    </div>

                    <!-- Category: President -->
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                        <span class="col-header">PRESIDENT</span>
                        <div class="d-flex gap-4">
                            <span class="col-header text-end" style="width: 50px;">VOTES</span>
                            <span class="col-header text-end" style="width: 60px;">TURNOUT</span>
                        </div>
                    </div>

                    <table class="table table-custom table-borderless mb-4">
                        <tbody>
                            <tr>
                                <td>Honey Malang</td>
                                <td class="text-end" style="width: 60px;">834</td>
                                <td class="text-end" style="width: 70px;">85%</td>
                            </tr>
                            <tr>
                                <td>Myles Macrohon</td>
                                <td class="text-end">522</td>
                                <td class="text-end">73%</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Category: Vice President -->
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                        <span class="col-header">VICE PRESIDENT</span>
                        <div class="d-flex gap-4">
                            <span class="col-header text-end" style="width: 50px;">VOTES</span>
                            <span class="col-header text-end" style="width: 60px;">TURNOUT</span>
                        </div>
                    </div>

                    <table class="table table-custom table-borderless">
                        <tbody>
                            <tr>
                                <td>Jahaira Ampaso</td>
                                <td class="text-end" style="width: 60px;">220</td>
                                <td class="text-end" style="width: 70px;">67%</td>
                            </tr>
                            <tr>
                                <td>Jose Perolino</td>
                                <td class="text-end">200</td>
                                <td class="text-end">66%</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

        </div> <!-- End Row -->
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
