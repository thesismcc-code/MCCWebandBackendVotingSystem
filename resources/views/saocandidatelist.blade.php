<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidates List</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0b2361; /* Deep navy background from sidebar */
            color: white;
            min-height: 100vh;
        }

        /* The main lighter blue container area */
        .main-panel {
            background-color: #103494; /* The brighter royal blue background */
            border-radius: 20px;
            padding: 2rem;
            min-height: 85vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Back Button Style */
        .btn-back {
            width: 35px;
            height: 35px;
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

        /* The Card Styling */
        .candidate-card {
            background: white;
            border-radius: 10px;
            border: 2px solid #289bf5; /* The cyan border outline */
            overflow: hidden; /* Keeps child elements inside corners */
            width: 100%;
            max-width: 700px; /* Constrain width to look like image */
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        /* Table Styling to match Image */
        .table-custom {
            margin-bottom: 0;
            color: #000;
        }

        .table-custom thead th {
            text-transform: uppercase;
            font-weight: 700;
            border-bottom: 2px solid #8FAADC; /* The blue line separator */
            padding-top: 20px;
            padding-bottom: 20px;
            padding-left: 40px;
            font-size: 0.9rem;
        }

        .table-custom tbody td {
            vertical-align: top;
            padding-top: 20px;
            padding-bottom: 20px;
            padding-left: 40px;
            border-bottom: 1px solid #8FAADC; /* Blue separators between rows */
            color: #333;
        }

        /* Remove border from very last row */
        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }

        .candidate-name {
            margin-bottom: 5px;
            display: block;
        }
    </style>
</head>
<body class="p-4">

    <!-- Header Section -->
    <div class="container-fluid mb-3">
        <div class="d-flex align-items-center gap-3">
            <a href="#" class="btn-back">
                <i class="bi bi-arrow-left stroke-width-2"></i>
            </a>
            <h4 class="mb-0 fw-bold">Candidates List</h4>
        </div>
    </div>

    <!-- Main Content Panel -->
    <div class="main-panel shadow-sm">

        <!-- White Card Table -->
        <div class="candidate-card">
            <div class="table-responsive">
                <table class="table table-custom table-borderless">
                    <thead>
                        <tr>
                            <!-- 40% width for position column to center the list slightly right -->
                            <th scope="col" style="width: 40%;">Position</th>
                            <th scope="col">Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- President Row -->
                        <tr>
                            <td>President</td>
                            <td>
                                <span class="candidate-name">Honey Malang</span>
                                <span class="candidate-name">Myles Macrohon</span>
                            </td>
                        </tr>

                        <!-- Vice President Row -->
                        <tr>
                            <td>Vice President</td>
                            <td>
                                <span class="candidate-name">Honey Malang</span>
                                <span class="candidate-name">Myles Macrohon</span>
                            </td>
                        </tr>

                        <!-- Senators Row -->
                        <tr>
                            <td>Senators</td>
                            <td>
                                <span class="candidate-name">Honey Malang</span>
                                <span class="candidate-name">Myles Macrohon</span>
                                <span class="candidate-name">Honey Malang</span>
                                <span class="candidate-name">Myles Macrohon</span>
                                <span class="candidate-name">Honey Malang</span>
                                <span class="candidate-name">Myles Macrohon</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS (Optional, depending on need) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
