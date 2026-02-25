<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAO Final Results</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- General Page Styling (From previous code) --- */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0b2361;
            color: white;
            min-height: 100vh;
        }

        .btn-back {
            width: 40px;
            height: 40px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0b2361;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .btn-back:hover {
            transform: scale(1.1);
            color: #0b2361;
        }

        .main-panel {
            background-color: #0b2b88;
            border-radius: 20px;
            padding: 30px;
            min-height: 80vh;
            margin-top: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        /* --- New Styles for Final Results --- */

        /* Publish Button */
        .btn-publish {
            background-color: #2ada0b; /* Bright Green */
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            transition: background-color 0.2s;
        }
        .btn-publish:hover {
            background-color: #24bd08;
            color: white;
        }

        /* Results Card */
        .result-card {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            height: 100%;
            color: black; /* Text is black inside cards */
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        /* Results Table */
        .table-results {
            width: 100%;
            margin-bottom: 0;
        }

        .table-results th {
            text-transform: uppercase;
            font-weight: 800;
            border-bottom: 2px solid #6b93d6; /* The distinct blue separator line */
            padding-bottom: 15px;
            font-size: 0.9rem;
            color: #000;
        }

        .table-results td {
            padding-top: 8px;
            padding-bottom: 8px;
            vertical-align: top;
            border: none; /* Default no border */
            font-size: 0.9rem;
            color: #333;
        }

        /* Blue Separator Line between sections (e.g., between President and VP) */
        .section-separator td {
            border-top: 1px solid #6b93d6 !important;
            padding-top: 15px;
        }

        .fw-heavy {
            font-weight: 800;
        }

        .winner-label {
            font-weight: 800;
            font-size: 0.85rem;
            text-transform: uppercase;
            margin-top: 5px;
        }

        .vote-count {
            text-align: right;
            font-weight: 500;
        }

        /* For the scrollable list on the right if it gets long */
        .candidate-list-wrapper {
            max-height: 600px;
            overflow-y: auto;
        }

    </style>
</head>

<body class="p-3 p-md-4">

    <!-- Header Section -->
    <div class="container-fluid mb-2 px-0">
        <div class="d-flex align-items-center gap-3">
            <a href="#" class="btn-back">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <h4 class="mb-0 fw-bold">Final Results</h4>
        </div>
    </div>

    <!-- Main Content Panel -->
    <div class="main-panel">

        <!-- Action Button (Aligned Right) -->
        <div class="d-flex justify-content-end mb-4">
            <button class="btn-publish">
                Publish Official Results
            </button>
        </div>

        <div class="row g-4">

            <!-- LEFT COLUMN: Detailed Breakdown -->
            <div class="col-lg-6">
                <div class="result-card">
                    <table class="table-results">
                        <thead>
                            <tr>
                                <th style="width: 35%;">POSITION</th>
                                <th style="width: 50%;">NAME</th>
                                <th class="text-end">VOTES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- PRESIDENT -->
                            <tr>
                                <td class="fw-heavy pt-4">PRESIDENT</td>
                                <td class="pt-4">Honey Malang</td>
                                <td class="vote-count pt-4">115</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Myles Macrohon</td>
                                <td class="vote-count">109</td>
                            </tr>
                            <tr class="mb-4">
                                <td class="winner-label">WINNER:</td>
                                <td class="fw-heavy">Honey Malang</td>
                                <td></td>
                            </tr>

                            <!-- VICE PRESIDENT -->
                            <tr class="section-separator">
                                <td class="fw-heavy">VICE PRESIDENT</td>
                                <td>Jose Perolino</td>
                                <td class="vote-count">115</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Jahaira Ampaso</td>
                                <td class="vote-count">109</td>
                            </tr>
                            <tr>
                                <td class="winner-label">WINNER:</td>
                                <td class="fw-heavy">Jose Perolino</td>
                                <td></td>
                            </tr>

                            <!-- SENATORS -->
                            <tr class="section-separator">
                                <td class="fw-heavy">SENATORS</td>
                                <td>James Cortes</td>
                                <td class="vote-count">115</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Carley Serato</td>
                                <td class="vote-count">109</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Breant Cortes</td>
                                <td class="vote-count">115</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Carley Cobarde</td>
                                <td class="vote-count">109</td>
                            </tr>
                            <!-- Added entries to match length in image -->
                            <tr>
                                <td></td>
                                <td>James Cortes</td>
                                <td class="vote-count">115</td>
                            </tr>
                             <tr>
                                <td></td>
                                <td>Carley Serato</td>
                                <td class="vote-count">109</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Breant Cortes</td>
                                <td class="vote-count">115</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Carley Cobarde</td>
                                <td class="vote-count">109</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- RIGHT COLUMN: Running Tally / Winners List -->
            <div class="col-lg-6">
                <div class="result-card">
                    <div class="candidate-list-wrapper">
                        <table class="table-results">
                            <thead>
                                <tr>
                                    <!-- Spacer column to match the gap in the image -->
                                    <th style="width: 35%;"></th>
                                    <th style="width: 50%;">NAME</th>
                                    <th class="text-end">VOTES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Regular Entries -->
                                <tr><td class="pt-4"></td><td class="pt-4">James Cortes</td><td class="vote-count pt-4">115</td></tr>
                                <tr><td></td><td>James Cortes</td><td class="vote-count">109</td></tr>
                                <tr><td></td><td>Carley Serato</td><td class="vote-count">115</td></tr>
                                <tr><td></td><td>Carley Serato</td><td class="vote-count">109</td></tr>
                                <tr><td></td><td>Breant Cortes</td><td class="vote-count">115</td></tr>
                                <tr><td></td><td>Breant Cortes</td><td class="vote-count">109</td></tr>
                                <tr><td class="pb-5"></td><td class="pb-5">Breant Cortes</td><td class="vote-count pb-5">115</td></tr>

                                <!-- Winners Block -->
                                <tr>
                                    <td class="winner-label text-center">WINNERS:</td>
                                    <td class="fw-heavy">Jose Perolino</td>
                                    <td class="vote-count">115</td>
                                </tr>
                                <tr><td></td><td class="fw-heavy">Myles Macrohon</td><td class="vote-count">109</td></tr>
                                <tr><td></td><td class="fw-heavy">Carley Cobarde</td><td class="vote-count">115</td></tr>
                                <tr><td></td><td class="fw-heavy">Jahaira Ampaso</td><td class="vote-count">109</td></tr>
                                <tr><td></td><td class="fw-heavy">Honey Malang</td><td class="vote-count">115</td></tr>
                                <tr><td></td><td class="fw-heavy">Myles Macrohon</td><td class="vote-count">115</td></tr>
                                <tr><td></td><td class="fw-heavy">Jose Perolino</td><td class="vote-count">109</td></tr>
                                <tr><td></td><td class="fw-heavy">James Cortes</td><td class="vote-count">109</td></tr>
                                <tr><td></td><td class="fw-heavy">Honey Malang</td><td class="vote-count">115</td></tr>
                                <tr><td></td><td class="fw-heavy">Jahaira Ampaso</td><td class="vote-count">115</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div> <!-- End Row -->
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
