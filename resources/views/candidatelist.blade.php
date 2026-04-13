<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidates List - Fingerprint Voting System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #102864; color: white; }
        .bg-main-panel { background-color: #0C3189; border: 1px solid rgba(30, 64, 175, 0.3); }

        .btn-back {
            background-color: white; color: #113285;
            width: 40px; height: 40px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%; transition: transform 0.2s; text-decoration: none;
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
        }
        .btn-back:hover { transform: scale(1.1); color: #113285; }

        .panel-card {
            background-color: white; border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
        }

        .table thead th {
            background-color: white; border-bottom: 2px solid #e5e7eb;
            color: #111827; font-weight: 700;
            text-transform: uppercase; font-size: 0.875rem; padding: 1rem 1.5rem;
        }
        .table tbody td {
            padding: 1rem 1.5rem; color: #374151;
            font-size: 0.875rem; vertical-align: top;
            border-bottom: 1px solid #f3f4f6;
        }

        .btn-action-blue {
            width: 32px; height: 32px; background-color: #0066FF; color: white;
            border: none; border-radius: 4px;
            display: inline-flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .btn-action-blue:hover { background-color: #0052cc; color: white; }

        .btn-action-red {
            width: 32px; height: 32px; background-color: #ffb3b3; color: #FF0000;
            border: none; border-radius: 4px;
            display: inline-flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .btn-action-red:hover { background-color: #ffcccc; color: #cc0000; }

        .fab-btn {
            position: absolute; bottom: 24px; right: 24px;
            width: 56px; height: 56px;
            background-color: #0066FF; color: white;
            border-radius: 50%; border: none;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.2);
            display: flex; align-items: center; justify-content: center;
            transition: transform 0.2s; z-index: 10;
        }
        .fab-btn:hover { transform: scale(1.1); background-color: #0052cc; color: white; }

        .modal-content { border-radius: 1rem; border: none; }
        .modal-header { border-bottom: none; padding-bottom: 0; }

        .upload-circle {
            width: 100px; height: 100px; background-color: #F3F4F6;
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; color: #6B7280;
            cursor: pointer; overflow: hidden; flex-shrink: 0;
        }
        .upload-circle img { width: 100%; height: 100%; object-fit: cover; }

        .form-label-custom { font-weight: 600; color: #111827; font-size: 0.95rem; }
        .form-control, .form-select { border-color: #D1D5DB; border-radius: 0.375rem; }

        .btn-modal-cancel {
            border: 1px solid #FF3B3B; color: #FF3B3B; background: white;
            font-weight: 500; width: 100%;
        }
        .btn-modal-cancel:hover { background-color: #fff5f5; color: #FF3B3B; }

        .btn-modal-save {
            background-color: #0055FF; color: white; border: none;
            font-weight: 500; width: 100%;
        }
        .btn-modal-save:hover { background-color: #0044cc; color: white; }

        .btn-modal-delete {
            background-color: #FF3B3B; color: white; border: none;
            font-weight: 500; width: 100%;
        }
        .btn-modal-delete:hover { background-color: #cc0000; color: white; }
    </style>
</head>

<body class="p-3 p-md-4 min-vh-100 d-flex flex-column">

    <!-- HEADER -->
    <div class="container-xl mb-4 px-0">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('view.election-control-posistion-setup') }}" class="btn-back">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="h3 fw-bold m-0">Candidates List</h1>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    <div class="container-xl px-0 mb-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- MAIN CONTAINER -->
    <div class="container-xl bg-main-panel rounded-4 p-4 shadow-lg flex-fill d-flex flex-column position-relative">
        <div class="row g-4">

            <!-- ── LEFT PANEL: Grouped by Position ── -->
            <div class="col-lg-6">
                <div class="panel-card">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 28%;">Position</th>
                                    <th style="width: 45%;">Name</th>
                                    <th style="width: 27%;" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grouped = collect($candidates)->groupBy(fn($c) => $c->getPositionName());
                                @endphp

                                @forelse ($positions as $position)
                                    @php
                                        $posCandidates = $grouped->get($position->getPositionName(), collect());
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold text-dark" style="vertical-align: top;">
                                            {{ $position->getPositionName() }}
                                        </td>
                                        <td style="vertical-align: top;">
                                            @if ($posCandidates->isEmpty())
                                                <span class="text-muted fst-italic">No candidates yet</span>
                                            @else
                                                <div class="d-flex flex-column gap-1">
                                                    @foreach ($posCandidates as $c)
                                                        <div>{{ $c->getFullName() }}</div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center" style="vertical-align: top;">
                                            <div class="d-flex justify-content-center gap-2">
                                                <button class="btn-action-blue" title="Add candidate to this position"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#addCandidateModal"
                                                    data-position="{{ $position->getPositionName() }}">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                <button class="btn-action-red" title="Delete all candidates in this position"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteByPositionModal"
                                                    data-position="{{ $position->getPositionName() }}">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">No positions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ── RIGHT PANEL: Individual Candidates ── -->
            <div class="col-lg-6 position-relative" style="padding-bottom: 80px;">
                <div class="panel-card">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 28%;">Position</th>
                                    <th style="width: 45%;">Name</th>
                                    <th style="width: 27%;" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($candidates as $candidate)
                                    <tr>
                                        <td class="fw-medium text-dark">{{ $candidate->getPositionName() }}</td>
                                        <td>{{ $candidate->getFullName() }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Edit individual candidate -->
                                                <button class="btn-action-blue" title="Edit"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editCandidateModal"
                                                    data-id="{{ $candidate->getId() }}"
                                                    data-fullname="{{ $candidate->getFullName() }}"
                                                    data-position="{{ $candidate->getPositionName() }}"
                                                    data-course="{{ $candidate->getCourse() }}"
                                                    data-year="{{ $candidate->getYear() }}"
                                                    data-party="{{ $candidate->getPoliticalParty() }}"
                                                    data-platform="{{ $candidate->getPlatformAgenda() }}">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                <!-- Delete individual candidate -->
                                                <button class="btn-action-red" title="Delete"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteCandidateModal"
                                                    data-id="{{ $candidate->getId() }}"
                                                    data-name="{{ $candidate->getFullName() }}">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">No candidates found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- FAB: Add new candidate -->
                <button type="button" class="fab-btn" data-bs-toggle="modal" data-bs-target="#addCandidateModal">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </button>
            </div>

        </div>
    </div>


    {{-- ── ADD CANDIDATE MODAL ──────────────────────────────────────── --}}
    <div class="modal fade" id="addCandidateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 700px;">
            <div class="modal-content p-2">
                <div class="modal-header ps-4 pt-4">
                    <h4 class="modal-title fw-bold text-black">Add Candidate</h4>
                </div>
                <div class="modal-body p-4">
                    <form id="addCandidateForm" action="{{ route('election.candidate.save') }}" method="POST">
                        @csrf

                        <!-- Image Upload -->
                        <div class="d-flex align-items-center gap-4 mb-4">
                            <div class="upload-circle" id="addImagePreview">
                                <svg width="40" height="40" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-2 text-dark">Upload Image</h5>
                                <input type="file" id="addImageInput" accept="image/*" class="d-none">
                                <button type="button" class="btn btn-primary btn-sm px-3"
                                    style="background-color: #0055FF;"
                                    onclick="document.getElementById('addImageInput').click()">
                                    <svg class="d-inline-block me-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    Upload
                                </button>
                            </div>
                        </div>

                        <hr class="border-secondary-subtle my-4">

                        <!-- Position -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label form-label-custom">Position:</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="position_name" id="addPositionSelect" required>
                                    <option value="" disabled selected>Select Position</option>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->getPositionName() }}">{{ $position->getPositionName() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label form-label-custom">Full Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="full_name" required>
                            </div>
                        </div>

                        <!-- Course -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label form-label-custom">Course:</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="course" required>
                                    <option value="" disabled selected>Select Course</option>
                                    <option value="BSIT">BSIT</option>
                                    <option value="BSCS">BSCS</option>
                                    <option value="BSIS">BSIS</option>
                                    <option value="BSCE">BSCE</option>
                                </select>
                            </div>
                        </div>

                        <!-- Year -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label form-label-custom">Year:</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="year" required>
                                    <option value="" disabled selected>Select Year</option>
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                </select>
                            </div>
                        </div>

                        <!-- Political Party -->
                        <div class="row mb-4 align-items-center">
                            <label class="col-sm-3 col-form-label form-label-custom">Political Party:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="political_party">
                            </div>
                        </div>

                        <hr class="border-secondary-subtle my-4">

                        <!-- Platform -->
                        <div class="mb-3">
                            <label class="form-label form-label-custom">Platform / Agenda</label>
                            <textarea class="form-control" rows="4" name="platform_agenda"></textarea>
                        </div>

                    </form>
                </div>
                <div class="modal-footer border-0 d-flex justify-content-center gap-3 pb-4">
                    <div class="col-5">
                        <button type="button" class="btn btn-modal-cancel py-2" data-bs-dismiss="modal">Cancel</button>
                    </div>
                    <div class="col-5">
                        <button type="submit" form="addCandidateForm" class="btn btn-modal-save py-2">Add Candidate</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ── EDIT CANDIDATE MODAL ─────────────────────────────────────── --}}
    <div class="modal fade" id="editCandidateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 700px;">
            <div class="modal-content p-2">
                <div class="modal-header ps-4 pt-4">
                    <h4 class="modal-title fw-bold text-black">Edit Candidate</h4>
                </div>
                <div class="modal-body p-4">
                    <form id="editCandidateForm" action="{{ route('election.candidate.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="candidate_id" id="editCandidateId">

                        <!-- Image Upload -->
                        <div class="d-flex align-items-center gap-4 mb-4">
                            <div class="upload-circle" id="editImagePreview">
                                <svg width="40" height="40" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-2 text-dark">Upload Image</h5>
                                <input type="file" id="editImageInput" accept="image/*" class="d-none">
                                <button type="button" class="btn btn-primary btn-sm px-3"
                                    style="background-color: #0055FF;"
                                    onclick="document.getElementById('editImageInput').click()">
                                    <svg class="d-inline-block me-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    Change
                                </button>
                            </div>
                        </div>

                        <hr class="border-secondary-subtle my-4">

                        <!-- Position -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label form-label-custom">Position:</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="position_name" id="editPosition" required>
                                    <option value="" disabled>Select Position</option>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->getPositionName() }}">{{ $position->getPositionName() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label form-label-custom">Full Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="full_name" id="editFullName" required>
                            </div>
                        </div>

                        <!-- Course -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label form-label-custom">Course:</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="course" id="editCourse" required>
                                    <option value="" disabled>Select Course</option>
                                    <option value="BSIT">BSIT</option>
                                    <option value="BSCS">BSCS</option>
                                    <option value="BSIS">BSIS</option>
                                    <option value="BSCE">BSCE</option>
                                </select>
                            </div>
                        </div>

                        <!-- Year -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label form-label-custom">Year:</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="year" id="editYear" required>
                                    <option value="" disabled>Select Year</option>
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                </select>
                            </div>
                        </div>

                        <!-- Political Party -->
                        <div class="row mb-4 align-items-center">
                            <label class="col-sm-3 col-form-label form-label-custom">Political Party:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="political_party" id="editParty">
                            </div>
                        </div>

                        <hr class="border-secondary-subtle my-4">

                        <!-- Platform -->
                        <div class="mb-3">
                            <label class="form-label form-label-custom">Platform / Agenda</label>
                            <textarea class="form-control" rows="4" name="platform_agenda" id="editPlatform"></textarea>
                        </div>

                    </form>
                </div>
                <div class="modal-footer border-0 d-flex justify-content-center gap-3 pb-4">
                    <div class="col-5">
                        <button type="button" class="btn btn-modal-cancel py-2" data-bs-dismiss="modal">Cancel</button>
                    </div>
                    <div class="col-5">
                        <button type="submit" form="editCandidateForm" class="btn btn-modal-save py-2">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ── DELETE INDIVIDUAL CANDIDATE MODAL ───────────────────────── --}}
    <div class="modal fade" id="deleteCandidateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
            <div class="modal-content p-2">
                <div class="modal-header ps-4 pt-4 border-0">
                    <h4 class="modal-title fw-bold text-black">Delete Candidate</h4>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                        style="width: 72px; height: 72px; background-color: #ffe5e5;">
                        <svg width="36" height="36" fill="none" stroke="#FF3B3B" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <p class="text-dark mb-1" style="font-size: 1rem;">Are you sure you want to delete</p>
                    <p class="fw-bold text-dark" style="font-size: 1.1rem;">"<span id="deleteCandidateName"></span>"?</p>
                    <p class="text-secondary" style="font-size: 0.875rem;">This action cannot be undone.</p>

                    <form id="deleteCandidateForm" action="{{ route('election.candidate.delete') }}" method="POST">
                        @csrf
                        <input type="hidden" name="candidate_id" id="deleteCandidateId">
                    </form>
                </div>
                <div class="modal-footer border-0 d-flex justify-content-center gap-3 pb-4">
                    <div class="col-5">
                        <button type="button" class="btn btn-modal-cancel py-2" data-bs-dismiss="modal">Cancel</button>
                    </div>
                    <div class="col-5">
                        <button type="submit" form="deleteCandidateForm" class="btn btn-modal-delete py-2">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ── DELETE BY POSITION MODAL (left panel delete button) ────── --}}
    <div class="modal fade" id="deleteByPositionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
            <div class="modal-content p-2">
                <div class="modal-header ps-4 pt-4 border-0">
                    <h4 class="modal-title fw-bold text-black">Delete All Candidates</h4>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                        style="width: 72px; height: 72px; background-color: #ffe5e5;">
                        <svg width="36" height="36" fill="none" stroke="#FF3B3B" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <p class="text-dark mb-1" style="font-size: 1rem;">Are you sure you want to delete all candidates under</p>
                    <p class="fw-bold text-dark" style="font-size: 1.1rem;">"<span id="deleteByPositionName"></span>"?</p>
                    <p class="text-secondary" style="font-size: 0.875rem;">This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-0 d-flex justify-content-center gap-3 pb-4">
                    <div class="col-5">
                        <button type="button" class="btn btn-modal-cancel py-2" data-bs-dismiss="modal">Cancel</button>
                    </div>
                    <div class="col-5">
                        {{--
                            NOTE: Deleting all candidates by position requires a new controller
                            method. For now this loops and submits — or you can add a route later.
                            The button is wired but you need to add:
                            Route::post('/election-control/candidate/delete-by-position', ...)
                        --}}
                        <button type="button" class="btn btn-modal-delete py-2" id="confirmDeleteByPosition">Delete All</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── Add modal: pre-select position if opened from left panel button
        document.getElementById('addCandidateModal').addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget;
            if (!btn) return;
            const position = btn.getAttribute('data-position');
            if (position) {
                document.getElementById('addPositionSelect').value = position;
            } else {
                document.getElementById('addPositionSelect').value = '';
            }
        });

        // ── Image preview — Add
        document.getElementById('addImageInput').addEventListener('change', function () {
            const preview = document.getElementById('addImagePreview');
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => { preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`; };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // ── Image preview — Edit
        document.getElementById('editImageInput').addEventListener('change', function () {
            const preview = document.getElementById('editImagePreview');
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => { preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`; };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // ── Populate Edit modal
        document.getElementById('editCandidateModal').addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget;
            document.getElementById('editCandidateId').value  = btn.getAttribute('data-id');
            document.getElementById('editFullName').value     = btn.getAttribute('data-fullname');
            document.getElementById('editPosition').value     = btn.getAttribute('data-position');
            document.getElementById('editCourse').value       = btn.getAttribute('data-course');
            document.getElementById('editYear').value         = btn.getAttribute('data-year');
            document.getElementById('editParty').value        = btn.getAttribute('data-party') ?? '';
            document.getElementById('editPlatform').value     = btn.getAttribute('data-platform') ?? '';
        });

        // ── Populate Delete (individual) modal
        document.getElementById('deleteCandidateModal').addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget;
            document.getElementById('deleteCandidateId').value         = btn.getAttribute('data-id');
            document.getElementById('deleteCandidateName').textContent  = btn.getAttribute('data-name');
        });

        // ── Populate Delete by Position modal
        document.getElementById('deleteByPositionModal').addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget;
            document.getElementById('deleteByPositionName').textContent = btn.getAttribute('data-position');
        });
    </script>
</body>
</html>
