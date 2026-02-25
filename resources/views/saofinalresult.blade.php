<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAO Final Results - Confirm Publish</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* --- General Page Styling (Keep existing) --- */
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
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            /* To make the panel look "behind" the modal, we rely on Bootstrap's modal-backdrop */
        }

        /* --- Existing Result Styles --- */
        .btn-publish {
            background-color: #2ada0b;
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

        .result-card {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            height: 100%;
            color: black;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table-results {
            width: 100%;
            margin-bottom: 0;
        }

        .table-results th {
            text-transform: uppercase;
            font-weight: 800;
            border-bottom: 2px solid #6b93d6;
            padding-bottom: 15px;
            font-size: 0.9rem;
            color: #000;
        }

        .table-results td {
            padding-top: 8px;
            padding-bottom: 8px;
            vertical-align: top;
            font-size: 0.9rem;
            color: #333;
        }

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

        .candidate-list-wrapper {
            max-height: 600px;
            overflow-y: auto;
        }

        /* --- CUSTOM MODAL STYLES (Based on Image) --- */
        .custom-modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            text-align: center;
            padding: 30px 20px;
        }

        /* Red Warning Icon Circle */
        .icon-box {
            width: 70px;
            height: 70px;
            background-color: #fde8ea;
            /* Very light pink/red */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
        }

        .icon-circle-inner {
            width: 50px;
            height: 50px;
            background-color: #f8caced4;
            /* Slightly darker pink */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bi-exclamation-circle-fill {
            color: #dc2626;
            /* Deep Red */
            font-size: 2.5rem;
            /* Using Bootstrap Icon's circle-fill directly or mimicking image circle */
        }

        /* Modal Typography */
        .modal-title-custom {
            font-weight: 800;
            font-size: 1.5rem;
            color: #000;
            margin-bottom: 10px;
        }

        .modal-text-custom {
            color: #555;
            font-size: 0.95rem;
            margin-bottom: 25px;
        }

        /* Modal Buttons */
        .modal-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn-modal-cancel {
            background-color: #dc2626;
            /* Red */
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 6px;
            font-weight: 600;
        }

        .btn-modal-cancel:hover {
            background-color: #b91c1c;
            color: white;
        }

        .btn-modal-submit {
            background-color: #2ada0b;
            /* Bright Green */
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 6px;
            font-weight: 600;
        }

        .btn-modal-submit:hover {
            background-color: #24bd08;
            color: white;
        }

        /* The Custom "Icon" to match image exactly:
           Outer Red Circle + Exclamation mark */
        .alert-icon-svg {
            width: 70px;
            height: 70px;
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

        <!-- Action Button (Triggers Modal) -->
        <div class="d-flex justify-content-end mb-4">
            <button class="btn-publish" data-bs-toggle="modal" data-bs-target="#confirmPublishModal">
                Publish Official Results
            </button>
        </div>

        <div class="row g-4">
            <!-- Left Card: Overview -->
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
                            <!-- Data Matches Image Background for context -->
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
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Card: Winners -->
            <div class="col-lg-6">
                <div class="result-card">
                    <table class="table-results">
                        <thead>
                            <tr>
                                <th style="width: 35%;"></th>
                                <th style="width: 50%;">NAME</th>
                                <th class="text-end">VOTES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="winner-label text-center pt-5">WINNERS:</td>
                                <td class="fw-heavy pt-5">Jose Perolino</td>
                                <td class="vote-count pt-5">115</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="fw-heavy">Myles Macrohon</td>
                                <td class="vote-count">109</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================== -->
    <!--     THE CONFIRM MODAL      -->
    <!-- ========================== -->
    <div class="modal fade" id="confirmPublishModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal-content">

                <!-- Icon Area (Custom SVG to match red exclamation circle) -->
                <div class="d-flex justify-content-center mb-3">
                    <!-- Building the icon: Light Red Bg -> Red Circle Border -> Exclamation -->
                    <svg width="80" height="80" viewBox="0 0 80 80" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <!-- Outer very light pink circle -->
                        <circle cx="40" cy="40" r="38" fill="#FEE2E2" />
                        <!-- Inner Red Circle stroke -->
                        <circle cx="40" cy="40" r="30" stroke="#DC2626" stroke-width="2.5"
                            fill="#FEF2F2" />
                        <!-- Exclamation Mark -->
                        <rect x="37" y="25" width="6" height="20" rx="3" fill="#DC2626" />
                        <circle cx="40" cy="53" r="3.5" fill="#DC2626" />
                    </svg>
                </div>

                <!-- Text Content -->
                <h3 class="modal-title-custom">Are you sure?</h3>
                <p class="modal-text-custom">
                    You want to publish the official results?
                </p>

                <!-- Actions -->
                <div class="modal-actions">
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <!-- Form Submission usually goes here -->
                    <button type="submit" class="btn btn-modal-submit">Submit</button>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Optional: Script to automatically show modal on load for demo purposes
         (Remove this script in production to only show on button click) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('confirmPublishModal'));
            myModal.show();
        });
    </script>
</body>

</html>
