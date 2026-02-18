<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidates List - Fingerprint Voting System</title>

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

        /* Custom Colors */
        .bg-main-panel {
            background-color: #0C3189;
            border: 1px solid rgba(30, 64, 175, 0.3);
        }

        .text-dark-blue { color: #113285; }

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

        /* Table Styling */
        .panel-card {
            background-color: white;
            border-radius: 0.5rem;
            overflow: hidden;
            height: 100%;
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
        }

        .table thead th {
            background-color: white;
            border-bottom: 2px solid #e5e7eb;
            color: #111827;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.875rem;
            padding: 1rem 1.5rem;
        }

        .table tbody td {
            padding: 1rem 1.5rem;
            color: #374151;
            font-size: 0.875rem;
            vertical-align: top;
            border-bottom: 1px solid #f3f4f6;
        }

        /* Action Buttons */
        .btn-action-blue {
            width: 32px; height: 32px;
            background-color: #0066FF; color: white;
            border: none; border-radius: 4px;
            display: inline-flex; align-items: center; justify-content: center;
        }
        .btn-action-blue:hover { background-color: #0052cc; color: white; }

        .btn-action-red {
            width: 32px; height: 32px;
            background-color: #ffb3b3; color: #FF0000;
            border: none; border-radius: 4px;
            display: inline-flex; align-items: center; justify-content: center;
        }
        .btn-action-red:hover { background-color: #ffcccc; color: #cc0000; }

        /* Floating Action Button */
        .fab-btn {
            position: absolute;
            bottom: 24px; right: 24px;
            width: 56px; height: 56px;
            background-color: #0066FF; color: white;
            border-radius: 50%; border: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
            display: flex; align-items: center; justify-content: center;
            transition: transform 0.2s;
            z-index: 10;
        }
        .fab-btn:hover { transform: scale(1.1); background-color: #0052cc; color: white; }

        /* MODAL STYLES */
        .modal-content { border-radius: 1rem; border: none; }
        .modal-header { border-bottom: none; padding-bottom: 0; }
        .modal-footer { border-top: 1px solid #e5e7eb; }

        /* Image Upload Circle */
        .upload-circle {
            width: 100px; height: 100px;
            background-color: #F3F4F6;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #6B7280;
        }

        /* Custom Form Controls */
        .form-label-custom { font-weight: 600; color: #111827; font-size: 0.95rem; }
        .form-control, .form-select { border-color: #D1D5DB; border-radius: 0.375rem; }

        /* Custom Buttons in Modal */
        .btn-modal-cancel {
            border: 1px solid #FF3B3B; color: #FF3B3B; background: white;
            font-weight: 500; width: 100%;
        }
        .btn-modal-cancel:hover { background-color: #fff5f5; color: #FF3B3B; }

        .btn-modal-add {
            background-color: #0055FF; color: white; border: none;
            font-weight: 500; width: 100%;
        }
        .btn-modal-add:hover { background-color: #0044cc; color: white; }
    </style>
</head>

<body class="p-3 p-md-4 min-vh-100 d-flex flex-column">

    <!-- HEADER SECTION -->
    <div class="container-xl mb-4 px-0">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('view.election-control-posistion-setup') }}" class="btn-back">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="h3 fw-bold m-0">Candidates List</h1>
        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="container-xl bg-main-panel rounded-4 p-4 shadow-lg flex-fill d-flex flex-column position-relative">

        <div class="row g-4 h-100">

            <!-- LEFT PANEL: Candidates by Position -->
            <div class="col-lg-6">
                <div class="panel-card">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 25%;">Position</th>
                                    <th style="width: 50%;">Name</th>
                                    <th style="width: 25%;" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- President Row -->
                                <tr>
                                    <td class="fw-medium text-dark">President</td>
                                    <td>
                                        <div class="mb-1">Honey Malang</div>
                                        <div>Myles Macrohon</div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn-action-blue"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                            <button class="btn-action-red"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Vice President Row -->
                                <tr>
                                    <td class="fw-medium text-dark">Vice President</td>
                                    <td>
                                        <div class="mb-1">Jose Perolino</div>
                                        <div>Jahaira Ampaso</div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn-action-blue"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                            <button class="btn-action-red"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Senators Row -->
                                <tr>
                                    <td class="fw-medium text-dark">Senators</td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <div>James Cortes</div>
                                            <div>Carley Serato</div>
                                            <div>Breant Cortes</div>
                                            <div>Carley Cobarde</div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn-action-blue"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                            <button class="btn-action-red"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- RIGHT PANEL: Candidates List (Alternative View) -->
            <div class="col-lg-6 position-relative">
                <div class="panel-card">
                    <div class="table-responsive h-100">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 25%;">Position</th>
                                    <th style="width: 50%;">Name</th>
                                    <th style="width: 25%;" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Matching your screenshot/code where some columns are empty -->
                                <tr><td></td><td>Honey Malang</td><td></td></tr>
                                <tr><td></td><td>Myles Macrohon</td><td></td></tr>
                                <tr><td></td><td>Honey Malang</td><td></td></tr>
                                <tr><td></td><td>Myles Macrohon</td><td></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Floating Action Button -->
                <button type="button" class="fab-btn" data-bs-toggle="modal" data-bs-target="#addCandidateModal">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- ADD CANDIDATE MODAL -->
    <div class="modal fade" id="addCandidateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 700px;">
            <div class="modal-content p-2">

                <!-- Header -->
                <div class="modal-header ps-4 pt-4">
                    <h4 class="modal-title fw-bold text-black">Add Candidates</h4>
                </div>

                <!-- Body -->
                <div class="modal-body p-4">
                    <form id="addCandidateForm">
                        @csrf

                        <!-- Image Upload Section -->
                        <div class="d-flex align-items-center gap-4 mb-4">
                            <div class="upload-circle">
                                <!-- Placeholder Image Icon -->
                                <svg width="40" height="40" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path></svg>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-2 text-dark">Upload Image</h5>
                                <button type="button" class="btn btn-primary btn-sm px-3" style="background-color: #0055FF;">
                                    <svg class="d-inline-block me-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    Upload
                                </button>
                            </div>
                        </div>

                        <hr class="border-secondary-subtle my-4">

                        <!-- Form Fields -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label form-label-custom">Full Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="fullname">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label form-label-custom">Course:</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="course">
                                    <option selected></option>
                                    <option value="BSIT">BSIT</option>
                                    <option value="BSCS">BSCS</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label form-label-custom">Year:</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="year">
                                    <option selected></option>
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4 align-items-center">
                            <label class="col-sm-3 col-form-label form-label-custom">Political Party:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="party">
                            </div>
                        </div>

                        <hr class="border-secondary-subtle my-4">

                        <!-- Platform/Agenda -->
                        <div class="mb-3">
                            <label class="form-label form-label-custom">Platform / Agenda</label>
                            <textarea class="form-control" rows="4" name="platform"></textarea>
                        </div>

                    </form>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 d-flex justify-content-center gap-3 pb-4">
                    <div class="col-5">
                        <button type="button" class="btn btn-modal-cancel py-2" data-bs-dismiss="modal">Cancel</button>
                    </div>
                    <div class="col-5">
                        <button type="submit" form="addCandidateForm" class="btn btn-modal-add py-2">Add Candidate</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Optional: Preview Modal
        // var myModal = new bootstrap.Modal(document.getElementById('addCandidateModal'));
        // myModal.show();
    </script>
</body>
</html>
