<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Position Setup - Fingerprint Voting System</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #102864;
            color: white;
        }

        /* Custom Colors matching your design */
        .bg-main-panel {
            background-color: #0C3189;
            border: 1px solid rgba(30, 64, 175, 0.3);
        }

        .text-dark-blue {
            color: #113285;
        }

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
        }

        .btn-back:hover {
            transform: scale(1.1);
            color: #113285;
        }

        .stat-icon {
            background-color: #0066FF;
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        /* Table Styling */
        .table-card {
            background-color: white;
            border-radius: 1rem;
            min-height: 400px;
            position: relative;
        }

        .table thead th {
            border-bottom: 1px solid #dee2e6;
            color: #111827;
            font-weight: 700;
            padding: 1.5rem 1rem;
        }

        .table tbody td {
            padding: 1.25rem 1rem;
            color: #4b5563;
            font-weight: 500;
            vertical-align: middle;
        }

        /* Action Buttons */
        .btn-action-blue {
            width: 36px;
            height: 36px;
            background-color: #0066FF;
            color: white;
            border: none;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-action-blue:hover {
            background-color: #0052cc;
            color: white;
        }

        .btn-action-red {
            width: 36px;
            height: 36px;
            background-color: #ffb3b3;
            color: #FF0000;
            border: none;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-action-red:hover {
            background-color: #ffcccc;
            color: #cc0000;
        }

        /* Floating Action Button */
        .fab-btn {
            position: absolute;
            bottom: 24px;
            right: 24px;
            width: 56px;
            height: 56px;
            background-color: #0066FF;
            color: white;
            border-radius: 50%;
            border: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
        }
        .fab-btn:hover {
            transform: scale(1.1);
            background-color: #0052cc;
            color: white;
        }

        /* Modal Customization to match image */
        .modal-content {
            border-radius: 1rem;
            border: none;
        }
        .modal-header {
            border-bottom: none;
            padding-bottom: 0;
        }
        .modal-footer {
            border-top: none;
            justify-content: center;
            gap: 10px;
        }
        .form-label {
            color: #333;
            font-weight: 500;
        }
    </style>
</head>

<body class="p-3 p-md-4 min-vh-100 d-flex flex-column">

    <!-- HEADER SECTION -->
    <div class="container-xl mb-4 px-0">
        <div class="d-flex align-items-center gap-3">
            <!-- Back Button -->
            <a href="{{ route('view.election-control') }}" class="btn-back shadow-sm">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="h3 fw-bold m-0 tracking-tight">Position Setup</h1>
        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="container-xl bg-main-panel rounded-4 p-4 p-md-5 shadow-lg flex-fill d-flex flex-column gap-4 position-relative">

        <!-- TOP STATS CARDS -->
        <div class="row g-4 justify-content-center">

            <!-- Total Positions Card -->
            <div class="col-md-6">
                <div class="card border-0 rounded-4 shadow h-100">
                    <div class="card-body d-flex align-items-center gap-3 p-3">
                        <div class="stat-icon flex-shrink-0">
                            <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <h2 class="h2 fw-bold text-dark m-0 lh-1">3</h2>
                            <p class="small text-secondary fw-medium m-0 mt-1">Total Positions</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Candidates Card -->
            <div class="col-md-6">
                <div class="card border-0 rounded-4 shadow h-100">
                    <div class="card-body d-flex align-items-center justify-content-between p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon flex-shrink-0">
                                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <h2 class="h2 fw-bold text-dark m-0 lh-1">19</h2>
                                <p class="small text-secondary fw-medium m-0 mt-1">Total Candidates</p>
                            </div>
                        </div>
                        <a href="{{route('view.election-control-candidate-list')}}" class="text-dark-blue link-underline link-underline-opacity-0">
                            <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- POSITIONS TABLE CARD -->
        <div class="table-card shadow-lg w-100 flex-fill overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="ps-4 ps-md-5">Position Name</th>
                            <th scope="col" class="text-center">Max Vote</th>
                            <th scope="col" class="ps-5">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Row 1 -->
                        <tr>
                            <td class="ps-4 ps-md-5">President</td>
                            <td class="text-center">1</td>
                            <td class="ps-5">
                                <div class="d-flex gap-2">
                                    <button class="btn-action-blue" title="Edit">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button class="btn-action-red" title="Delete">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 2 -->
                        <tr>
                            <td class="ps-4 ps-md-5">Vice President</td>
                            <td class="text-center">1</td>
                            <td class="ps-5">
                                <div class="d-flex gap-2">
                                    <button class="btn-action-blue">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button class="btn-action-red">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 3 -->
                        <tr>
                            <td class="ps-4 ps-md-5">Senators</td>
                            <td class="text-center">9</td>
                            <td class="ps-5">
                                <div class="d-flex gap-2">
                                    <button class="btn-action-blue">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button class="btn-action-red">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Floating Action Button (Triggers Modal) -->
            <button type="button" class="fab-btn" data-bs-toggle="modal" data-bs-target="#addPositionModal">
                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            </button>
        </div>
    </div>

    <!-- ADD POSITION MODAL -->
<!-- Make sure this is placed before the closing </body> tag -->
<div class="modal fade" id="addPositionModal" tabindex="-1" aria-labelledby="addPositionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 600px;">
        <div class="modal-content shadow-lg border-0" style="border-radius: 24px; padding: 20px;">

            <!-- Modal Header -->
            <div class="modal-header border-0 pb-0 ps-4 pt-4">
                <h4 class="modal-title fw-bold text-black" id="addPositionModalLabel" style="font-size: 1.75rem;">Add Position</h4>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4 pt-5">
                <form id="addPositionForm">
                    <!-- Position Name Field -->
                    <div class="row mb-4 align-items-center">
                        <label for="positionName" class="col-sm-4 col-form-label text-dark fs-5" style="font-weight: 400;">Position Name:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-lg rounded-1 border-secondary-subtle" id="positionName" placeholder="Position Name" style="font-size: 0.95rem; padding: 10px 15px;">
                        </div>
                    </div>

                    <!-- Max Votes Field -->
                    <div class="row mb-4 align-items-center">
                        <label for="maxVotes" class="col-sm-4 col-form-label text-dark fs-5" style="font-weight: 400;">Max Votes:</label>
                        <div class="col-sm-8">
                            <select class="form-select form-select-lg rounded-1 border-secondary-subtle text-secondary" id="maxVotes" style="font-size: 0.95rem; padding: 10px 15px;">
                                <option selected disabled>Select</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-0 justify-content-end pe-4 pb-4 gap-3">
                <button type="button" class="btn px-4 py-2" data-bs-dismiss="modal"
                    style="border: 1px solid #FF3B3B; color: #FF3B3B; background: white; width: 120px; font-weight: 500;">
                    Cancel
                </button>
                <button type="submit" form="addPositionForm" class="btn px-4 py-2 text-white"
                    style="background-color: #00CC00; border: none; width: 120px; font-weight: 500;">
                    Save
                </button>
            </div>

        </div>
    </div>
</div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script to handle modal open if needed automatically, or for handling form submission -->
    <script>
        // Optional: If you want to preview the modal immediately like the screenshot
        // var myModal = new bootstrap.Modal(document.getElementById('addPositionModal'));
        // myModal.show();
    </script>
</body>
</html>
