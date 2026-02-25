@extends('components.sao-layout')

@section('title', 'SAO Dashboard')

@section('content')

    <!-- Styles specific to this dashboard to match the screenshot -->
    <style>
        /* Dark Blue Background Wrapper */
        .dashboard-wrapper {
            background-color: #0d2879;
            /* Deep blue from screenshot */
            color: white;
            border-radius: 20px;
            min-height: 80vh;
            /* Fill space */
            padding: 30px;
            font-family: sans-serif;
        }

        /* Cards */
        .custom-card {
            border-radius: 12px;
            border: none;
            background-color: white;
            color: #000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 25px;
        }

        /* The Cyan/Blue Outline for the Candidates Card (Matches Image) */
        .card-highlight-cyan {
            border: 2px solid #5aa9fa;
            /* Bright cyan-blue border */
        }

        /* Status Dot */
        .status-dot-active {
            height: 12px;
            width: 12px;
            background-color: #dc3545;
            /* Red color */
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        /* "View" Button Pill */
        .btn-view-custom {
            background-color: #cadcfc;
            /* Very light blue bg */
            color: #38558a;
            /* Dark blue text */
            border-radius: 50px;
            padding: 6px 20px;
            font-size: 0.85rem;
            font-weight: 700;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .btn-view-custom:hover {
            background-color: #a8c4fa;
            color: #2a416a;
        }

        /* Table Styles */
        .table-custom thead th {
            border-bottom: 2px solid #eee;
            font-weight: 700;
            color: #000;
            padding-bottom: 15px;
        }

        .table-custom td {
            padding-top: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }

        /* Last row should not have a border if visually preferring the image exactness */
        .table-custom tr:last-child td {
            border-bottom: none;
        }
    </style>

    <!-- Dashboard Heading -->
    <h2 class="text-2xl font-bold mb-6">Dashboard</h2>

    <!-- 1. ELECTION OVERVIEW CARD (White, No Border) -->
    <div class="card custom-card">
        <h5 class="fw-bold text-uppercase mb-4">ELECTION OVERVIEW</h5>

        <!-- Grid Layout for Data -->
        <div class="row mb-3">
            <div class="col-md-3 col-sm-4 fw-bold">Election Name:</div>
            <div class="col-md-9 col-sm-8">MCC ELECTION 2025</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-md-3 col-sm-4 fw-bold">Status:</div>
            <div class="col-md-9 col-sm-8 d-flex align-items-center">
                <span class="status-dot-active"></span>
                <span>Ongoing</span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 col-sm-4 fw-bold">Schedule:</div>
            <div class="col-md-9 col-sm-8">12-12-2025 8:00AM - 12-12-2025 5:00PM</div>
        </div>
    </div>

    <!-- 2. CANDIDATES LIST CARD (Blue Border Highlight) -->
    <div class="card custom-card card-highlight-cyan">

        <!-- Header + Button Flex Container -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-bold text-uppercase m-0">CANDIDATES LIST</h5>

            <!-- View Button -->
            <button type="button" class="btn btn-view-custom">
                <!-- Eye Icon (Using Bootstrap Icons class, assume icons are loaded) -->
                <i class="bi bi-eye-fill"></i> View
            </button>
        </div>

        <!-- Candidate Table -->
        <div class="table-responsive">
            <table class="table table-borderless table-custom w-100 mb-0">
                <thead>
                    <tr>
                        <th scope="col" style="width: 40%;">Position</th>
                        <th scope="col">Name</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- President -->
                    <tr>
                        <td>President</td>
                        <td>
                            <div class="mb-2">Honey Malang</div>
                            <div>Myles Macrohon</div>
                        </td>
                    </tr>
                    <!-- Vice President -->
                    <tr>
                        <td>Vice President</td>
                        <td>
                            <div class="mb-2">Jahaira Ampaso</div>
                            <div>Jose Perolino</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
