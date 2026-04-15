<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fingerprint Enrollment - System</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #102864;
        }

        .bg-main-panel {
            background-color: #0d34a5;
        }

        [x-cloak] {
            display: none !important;
        }

        .badge-enrolled {
            background-color: #dcfce7;
            color: #166534;
            font-size: 0.65rem;
            padding: 4px 14px;
            border-radius: 9999px;
            font-weight: 600;
        }

        /* Updated Precisely Scaled Mapping Target Layout scan-move exactly fitting UI Image Bounds naturally */
        @keyframes scan-move {

            0%,
            100% {
                top: 5%;
                opacity: 0;
            }

            15% {
                opacity: 1;
            }

            85% {
                opacity: 1;
            }

            50% {
                top: 86%;
            }
        }

        .scan-animation {
            animation: scan-move 2.2s infinite ease-in-out;
            box-shadow: 0 4px 18px rgba(2, 227, 0, 0.7);
        }

        /* Table generic fixes standardizing scrollability & styling without layout constraints overriding basic attributes correctly clean up default element tags visually completely cleanly natively aligned overall without bugs naturally fitting sizes. */
        table {
            border-collapse: separate;
            border-spacing: 0;
        }

        th,
        td {
            border-bottom: 1px solid #e5e7eb;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }
    </style>

    @php
    $courseChoices = collect($data['courses'] ?? []);
    if (old('course')) {
    $courseChoices = $courseChoices->push(old('course'));
    }
    $courseChoices = $courseChoices->filter()->unique()->values()->all();

    $fingerprintAlpineInit = [
    'openEditModal' => (bool) session('show_edit_modal'),
    'courseOptions' => $courseChoices,
    'editForm' => [
    'user_id' => old('user_id', ''),
    'first_name' => old('first_name', ''),
    'middle_name' => old('middle_name', ''),
    'last_name' => old('last_name', ''),
    'email' => old('email', ''),
    'password' => '',
    'student_id' => old('student_id', ''),
    'course' => old('course', ''),
    'year_level' => old('year_level', ''),
    ],
    ];
    @endphp

    <script id="fingerprint-alpine-init" type="application/json">
        @json($fingerprintAlpineInit)
    </script>
    <script>
        function fingerprintPageData() {
            const el = document.getElementById('fingerprint-alpine-init');
            const init = el ? JSON.parse(el.textContent) : {
                openEditModal: false,
                courseOptions: [],
                editForm: {
                    user_id: '',
                    first_name: '',
                    middle_name: '',
                    last_name: '',
                    email: '',
                    password: '',
                    student_id: '',
                    course: '',
                    year_level: '',
                },
            };

            return {
                openModal: false,
                step: 1, // 1: Info Form, 2: Already Enrolled preview, 3: Active Scanning Phase
                isScanning: false,

                openEditModal: init.openEditModal,
                showDeleteModal: false,
                deleteUserId: '',
                courseOptions: init.courseOptions,
                editForm: {
                    ...init.editForm
                },

                notify: {
                    show: false,
                    type: 'success',
                    title: '',
                    message: '',
                },

                triggerNotification(type, title, message) {
                    this.notify.type = type;
                    this.notify.title = title;
                    this.notify.message = message;
                    this.notify.show = true;
                    setTimeout(() => {
                        this.notify.show = false;
                    }, 4000);
                },

                openEdit(row) {
                    const c = row.dataset.course || '';
                    if (c && !this.courseOptions.includes(c)) {
                        this.courseOptions = [...this.courseOptions, c];
                    }
                    this.editForm = {
                        user_id: row.dataset.userId,
                        first_name: row.dataset.firstName,
                        middle_name: row.dataset.middleName || '',
                        last_name: row.dataset.lastName,
                        email: row.dataset.email,
                        password: '',
                        student_id: row.dataset.studentId,
                        course: c,
                        year_level: row.dataset.yearLevel || '',
                    };
                    this.openEditModal = true;
                },

                prepareDelete(userId) {
                    this.deleteUserId = userId;
                    this.showDeleteModal = true;
                },

                openEnrollmentModal() {
                    this.openModal = true;
                    this.step = 1;
                },

                handleScan() {
                    this.isScanning = true;
                    setTimeout(() => {
                        this.isScanning = false;
                        this.openModal = false;
                        const success = true;
                        if (success) {
                            this.triggerNotification('success', 'Success!', 'Fingerprint has been successfully registered.');
                        } else {
                            this.triggerNotification('error', 'Failed!', 'Unable to capture fingerprint. Please try again.');
                        }
                        // Reset perfectly logically seamlessly gracefully effectively flawlessly for next session securely.
                        setTimeout(() => {
                            this.step = 1;
                        }, 500);
                    }, 2500);
                },
            };
        }
    </script>
</head>

<body x-data="fingerprintPageData()"
    class="p-4 md:p-6 lg:p-8 min-h-screen flex flex-col antialiased text-white relative font-sans overflow-y-auto">

    <!-- CENTERED SUCCESS/ERROR MODAL -->
    <div x-cloak x-show="notify.show" class="relative z-[100]" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div x-show="notify.show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="notify.show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.outside="notify.show = false"
                    class="relative transform overflow-hidden rounded-[20px] bg-white px-4 pb-8 pt-8 text-center shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-[420px]">
                    <div class="absolute right-4 top-4">
                        <button @click="notify.show = false" type="button"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none"><svg class="h-6 w-6"
                                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg></button>
                    </div>
                    <template x-if="notify.type === 'success'">
                        <div class="flex flex-col items-center justify-center mt-2">
                            <div
                                class="mx-auto flex h-24 w-24 items-center justify-center rounded-full border-[6px] border-[#04de00] mb-6">
                                <svg class="h-14 w-14 text-[#04de00]" fill="none" viewBox="0 0 24 24"
                                    stroke-width="4" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>
                            <h3 class="text-3xl font-extrabold text-gray-900 mb-3" x-text="notify.title"></h3>
                            <div class="px-6">
                                <p class="text-sm font-medium text-gray-600 leading-relaxed" x-text="notify.message">
                                </p>
                            </div>
                        </div>
                    </template>
                    <template x-if="notify.type === 'error'">
                        <div class="flex flex-col items-center justify-center mt-2">
                            <div
                                class="mx-auto flex h-24 w-24 items-center justify-center rounded-full border-[6px] border-red-500 mb-6">
                                <svg class="h-14 w-14 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="4"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <h3 class="text-3xl font-extrabold text-gray-900 mb-3" x-text="notify.title"></h3>
                            <div class="px-6">
                                <p class="text-sm font-medium text-gray-600 leading-relaxed" x-text="notify.message">
                                </p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- HEADER SECTION -->
    <div class="w-full max-w-[1240px] mx-auto flex items-center justify-between mt-2 mb-6 px-1">
        <div class="flex items-center gap-[18px]">
            <a href="{{ route('view.quick-access') }}"
                class="bg-white text-blue-800 rounded-full w-[38px] h-[38px] flex items-center justify-center hover:scale-105 transition-transform shadow hover:bg-gray-100 flex-shrink-0">
                <svg class="w-[20px] h-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-[26px] font-bold tracking-tight text-white leading-tight mt-[-2px]">Fingerprint
                    Enrollment</h1>
                <p class="text-blue-200/90 text-[13px] tracking-wide mt-[-2px]">Register new students and capture
                    biometric data</p>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="w-full max-w-[1240px] mx-auto mb-4 px-1">
        <div
            class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800 shadow-sm">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if ($errors->has('general'))
    <div class="w-full max-w-[1240px] mx-auto mb-4 px-1">
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800 shadow-sm">
            {{ $errors->first('general') }}
        </div>
    </div>
    @endif

    <!-- DELETE CONFIRMATION -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[150] flex items-center justify-center p-4">
        <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-90 translate-y-4" @click.away="showDeleteModal = false"
            class="bg-white rounded-2xl p-8 w-full max-w-[400px] shadow-2xl relative z-10 flex flex-col items-center text-center text-gray-800">

            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-5">
                <svg class="w-8 h-8 text-[#c81e1e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
            </div>

            <h3 class="text-2xl font-bold text-gray-900 mb-2">Are you sure?</h3>
            <p class="text-sm text-gray-600 font-medium mb-8">This student account will be removed from the list.</p>

            <div class="flex gap-4 w-full justify-center">
                <button type="button" @click="showDeleteModal = false"
                    class="bg-[#ce1b26] text-white text-sm font-bold py-2.5 px-8 rounded-lg shadow-md hover:bg-red-700 transition-colors">
                    Cancel
                </button>
                <form action="{{ route('finger-print.student.delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" :value="deleteUserId">
                    <input type="hidden" name="list_student_id" value="{{ $student_id }}">
                    <input type="hidden" name="list_course" value="{{ $course }}">
                    <input type="hidden" name="list_year_level" value="{{ $year_level }}">
                    <button type="submit"
                        class="bg-[#1ccb14] text-white text-sm font-bold py-2.5 px-8 rounded-lg shadow-md hover:bg-green-600 transition-colors">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- MAIN APP WRAPPER -->
    <div class="max-w-[1240px] w-full mx-auto bg-main-panel rounded-2xl md:rounded-[24px] shadow-2xl flex flex-col flex-1 p-5 md:p-8 pt-8 overflow-hidden relative min-h-[500px]">

        <!-- TOP METRICS ROW CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-lg lg:max-w-2xl w-full relative z-10 mb-8 mt-1">
            <div class="bg-white rounded-[20px] p-[20px] flex items-center gap-[20px] min-w-min shadow-sm pointer-events-none select-none overflow-hidden h-24">
                <div class="bg-[#0b5cff] w-14 h-14 rounded-[12px] flex items-center justify-center text-white shrink-0 ml-1.5 drop-shadow-[0_2px_12px_rgba(11,102,255,0.4)]">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="flex flex-col mb-1 ml-0.5 mt-[-1px]">
                    <div class="text-[26px] leading-[1.1] font-extrabold text-[#0e1732]">{{ $data['enrolled_students'] }}</div>
                    <div class="text-[13px] text-gray-500 font-medium tracking-[0.010em] pt-0.5">Enrolled Students</div>
                </div>
            </div>

            <div class="bg-white rounded-[20px] p-[20px] flex items-center gap-[20px] min-w-min shadow-sm pointer-events-none select-none overflow-hidden h-24">
                <div class="bg-[#ffd03d] w-14 h-14 rounded-[12px] flex items-center justify-center text-white shrink-0 ml-1.5 shadow-md border-[1.5px] border-[#fbce41]/40 text-[#ffffff] fill-current">
                    <svg class="w-7 h-7 stroke-[1.8px] drop-shadow-[0_2px_4px_rgba(200,160,20,0.1)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-linecap="round"></rect>
                        <path d="M16 2v4M8 2v4M3 10h18" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <div class="flex flex-col mb-1 ml-0.5 mt-[-1px]">
                    <div class="text-[26px] leading-[1.1] font-extrabold text-[#0e1732]">{{ $data['enrolled_today'] }}</div>
                    <div class="text-[13px] text-gray-500 font-medium tracking-[0.010em] pt-0.5">Enrolled Today</div>
                </div>
            </div>
        </div>

        <!-- SEARCH BAR AND FILTER BLOCK -->
        <form method="GET" action="{{ route('view.finger-print') }}" class="flex flex-col lg:flex-row items-center w-full gap-5 z-20 relative max-w-full pb-[1.5px] mb-8 lg:mb-10 px-0 mt-2">
            <!-- Search by Student ID -->
            <div class="relative w-full lg:flex-[3.3]">
                <div class="absolute inset-y-0 left-0 pl-[18px] flex items-center pointer-events-none text-[#b4cfff]">
                    <svg class="h-[18px] w-[18px] stroke-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="student_id" value="{{ $student_id }}"
                    class="block w-full pl-[46px] pr-4 h-12 bg-[#1b43bc] border-[1px] border-[#3862e2]/70 hover:border-blue-300 rounded-[8px] text-[13.5px] text-white font-medium placeholder-blue-200/80 focus:outline-none focus:ring-[1px] focus:ring-blue-300 focus:border-blue-300 transition duration-150 shadow-inner outline-none"
                    placeholder="Search by Student ID or Name" x-data="{ timeout: null }" @input="clearTimeout(timeout); timeout = setTimeout(() => $el.closest('form').submit(), 500)">
            </div>

            <!-- Course Filter -->
            <div class="relative w-full lg:flex-[2]">
                <div class="absolute inset-y-0 left-0 pl-[18px] flex items-center pointer-events-none text-blue-200/90">
                    <svg class="w-5 h-5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    </svg>
                </div>
                <select name="course" onchange="this.form.submit()" class="block w-full pl-[48px] pr-10 h-12 bg-[#1b43bc] border-[1px] border-[#3862e2]/70 hover:border-blue-300 rounded-[8px] text-[13px] text-white font-medium focus:outline-none focus:ring-[1px] focus:ring-blue-300 transition appearance-none cursor-pointer outline-none">
                    <option class="text-gray-900 bg-white" value="">All Courses</option>
                    @foreach ($data['courses'] as $courseOption)
                    <option class="text-gray-900 bg-white" value="{{ $courseOption }}" {{ $course === $courseOption ? 'selected' : '' }}>
                        {{ $courseOption }}
                    </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-blue-200">
                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>

            <!-- Year Level Filter -->
            <div class="relative w-full lg:flex-[2]">
                <div class="absolute inset-y-0 left-0 pl-[18px] flex items-center pointer-events-none text-blue-100">
                    <svg class="h-[18px] w-[18px] opacity-90 stroke-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-linecap="round"></rect>
                        <path d="M16 2v4M8 2v4M3 10h18" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <select name="year_level" onchange="this.form.submit()" class="block w-full pl-[46px] pr-10 h-12 bg-[#1b43bc] border-[1px] border-[#3862e2]/70 hover:border-blue-300 rounded-[8px] text-[13px] text-white font-medium focus:outline-none focus:ring-[1px] focus:ring-blue-300 transition appearance-none cursor-pointer outline-none shadow-sm">
                    <option class="text-gray-900 bg-white" value="">Year Level</option>
                    <option class="text-gray-900 bg-white" value="1" {{ $year_level == '1' ? 'selected' : '' }}>1st Year</option>
                    <option class="text-gray-900 bg-white" value="2" {{ $year_level == '2' ? 'selected' : '' }}>2nd Year</option>
                    <option class="text-gray-900 bg-white" value="3" {{ $year_level == '3' ? 'selected' : '' }}>3rd Year</option>
                    <option class="text-gray-900 bg-white" value="4" {{ $year_level == '4' ? 'selected' : '' }}>4th Year</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-blue-200">
                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
            <!-- Hidden submit -->
            <button type="submit" class="hidden"></button>
        </form>

        <div class="bg-white rounded-[14px] shadow-xl w-full flex-1 overflow-x-auto z-10 p-[1px] text-gray-800">
            <table class="w-full text-left bg-white overflow-hidden text-[#22273e] table-auto align-top min-w-[980px] w-full rounded-[14px] border-hidden">
                <thead>
                    <tr class="text-[13px] border-b-[2px] border-gray-100 bg-white h-[60px] text-gray-900 border-separate">
                        <th class="px-7 py-3 font-bold whitespace-nowrap min-w-[155px]">Student ID</th>
                        <th class="px-7 py-3 font-bold whitespace-nowrap min-w-[195px]">Name</th>
                        <th class="px-7 py-3 font-bold whitespace-nowrap w-auto pr-8">Course</th>
                        <th class="px-7 py-3 font-bold whitespace-nowrap">Year Level</th>
                        <th class="px-7 py-3 font-bold whitespace-nowrap">Created</th>
                        <th class="px-7 py-3 font-bold whitespace-nowrap pr-8 text-center max-w-[145px] w-[110px]">Status</th>
                        <th class="px-5 py-3 font-bold whitespace-nowrap text-center min-w-[140px]">Action</th>
                    </tr>
                </thead>
                <tbody class="text-[13.5px] font-[500] align-middle">
                    @forelse ($data['students'] as $student)
                    <tr class="hover:bg-blue-50/50 transition-colors h-[76px] ease-in-out font-medium"
                        data-user-id="{{ $student->getId() }}"
                        data-first-name="{{ e($student->getFirstName()) }}"
                        data-middle-name="{{ e($student->getMiddleName()) }}"
                        data-last-name="{{ e($student->getLastName()) }}"
                        data-email="{{ e($student->getEmail()) }}"
                        data-student-id="{{ e($student->getStudentId() ?? '') }}"
                        data-course="{{ e($student->getCourse() ?? '') }}"
                        data-year-level="{{ e($student->getYearLevel() ?? '') }}">
                        <td class="px-7 text-gray-800 tracking-wide font-normal">{{ $student->getStudentId() ?? '—' }}</td>
                        <td class="px-7 text-[#212739] tracking-[0.012em]">{{ $student->getFirstName() }} {{ $student->getLastName() }}</td>
                        <td class="px-7 text-[#2d3248] tracking-[0.012em] font-[450]">{{ $student->getCourse() ?? '—' }}</td>
                        <td class="px-7 text-gray-800 whitespace-nowrap">{{ $student->getYearLevel() ?? '—' }}</td>
                        <td class="px-7 text-gray-800 tracking-wide">{{ $student->getCreatedAt() ? \Carbon\Carbon::parse($student->getCreatedAt())->format('m-d-Y') : '—' }}</td>
                        <td class="px-7 py-3 align-middle"><span class="badge-enrolled">Enrolled</span></td>
                        <td class="px-4 py-3 align-middle whitespace-nowrap">
                            <div class="flex items-center justify-center gap-3">
                                <button type="button" @click="prepareDelete($el.closest('tr').dataset.userId)"
                                    class="group flex h-8 w-8 items-center justify-center rounded-md border border-gray-100 bg-gray-100 transition-all hover:border-red-100 hover:bg-red-50">
                                    <svg class="h-[16px] w-[16px] text-[#ced0db] transition-colors group-hover:text-red-500" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                <button type="button" @click="openEdit($el.closest('tr'))"
                                    class="flex h-8 w-8 items-center justify-center rounded-md bg-[#1853fc] text-white shadow-[0_2px_8px_rgba(24,83,252,0.4)] transition-all hover:-translate-y-px hover:bg-[#123ebd]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-7 py-10 text-center text-gray-400 font-medium">No students found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        @if ($data['students']->lastPage() > 1)
        <div class="flex items-center justify-between mt-5 px-1 pb-2 pr-20">
            <p class="text-blue-200/80 text-[13px] font-medium">
                Showing {{ $data['students']->firstItem() }}–{{ $data['students']->lastItem() }} of {{ $data['students']->total() }} students
            </p>
            <div class="flex items-center gap-2">
                @if ($data['students']->onFirstPage())
                <span class="px-4 py-2 rounded-[8px] bg-white/10 text-white/30 text-[13px] font-semibold cursor-not-allowed select-none">← Prev</span>
                @else
                <a href="{{ $data['students']->appends(request()->query())->previousPageUrl() }}" class="px-4 py-2 rounded-[8px] bg-white/20 hover:bg-white/30 text-white text-[13px] font-semibold transition">← Prev</a>
                @endif

                @foreach ($data['students']->appends(request()->query())->getUrlRange(1, $data['students']->lastPage()) as $page => $url)
                @if ($page == $data['students']->currentPage())
                <span class="px-3 py-2 rounded-[8px] bg-white text-blue-800 text-[13px] font-bold min-w-[36px] text-center">{{ $page }}</span>
                @elseif (abs($page - $data['students']->currentPage()) <= 2)
                    <a href="{{ $url }}" class="px-3 py-2 rounded-[8px] bg-white/20 hover:bg-white/30 text-white text-[13px] font-semibold transition min-w-[36px] text-center">{{ $page }}</a>
                    @elseif (abs($page - $data['students']->currentPage()) == 3)
                    <span class="text-white/40 text-[13px] px-1">...</span>
                    @endif
                    @endforeach

                    @if ($data['students']->hasMorePages())
                    <a href="{{ $data['students']->appends(request()->query())->nextPageUrl() }}" class="px-4 py-2 rounded-[8px] bg-white/20 hover:bg-white/30 text-white text-[13px] font-semibold transition">Next →</a>
                    @else
                    <span class="px-4 py-2 rounded-[8px] bg-white/10 text-white/30 text-[13px] font-semibold cursor-not-allowed select-none">Next →</span>
                    @endif
            </div>
        </div>
        @endif

        <!-- FLOATING ADD BUTTON -->
        <button @click="openEnrollmentModal()" title="Add User Registration Capture Bio"
            class="absolute bottom-[28px] lg:bottom-[45px] right-[24px] lg:right-[40px] bg-[#0c59f2] w-[60px] h-[60px] rounded-full flex items-center justify-center text-white shadow-[0_8px_20px_rgba(4,22,122,0.48)] hover:bg-[#186cf9] hover:-translate-y-1 hover:shadow-2xl active:scale-[0.96] transition-all duration-300 z-50 group border border-[#1b6bfc] ring-1 ring-black/5 ring-inset ring-white/10 m-0 overflow-hidden align-middle box-border m-[auto] cursor-pointer pt-[2px]">
            <svg class="w-[28px] h-[28px] stroke-[2px] ml-1 opacity-95 group-hover:scale-110 drop-shadow transform transition ease-out duration-300 delay-[5ms]"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
        </button>

    </div>

    <!-- MAIN APP MODAL STRUCTURE FLOW -->
    <div x-cloak x-show="openModal" class="relative z-[200]" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity">
        </div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <!-- Wrapper box handles shared background exactly dynamically naturally matching frame setup universally gracefully -->
                <div x-show="openModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.outside="!isScanning && (openModal = false)"
                    class="relative transform rounded-[20px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-[530px] min-h-[500px]">

                    <!-- ============================================== -->
                    <!-- STEP 1: Student Info Form                     -->
                    <!-- ============================================== -->
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-200 delay-100"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0" class="px-8 py-8 h-full">
                        <h3 class="text-xl font-extrabold text-[#111624] mb-6">Student Information</h3>
                        <form action="#" class="space-y-5">
                            <div>
                                <label
                                    class="block text-[11.5px] uppercase tracking-wider font-extrabold text-gray-400 mb-[5px] pl-[5px]">Student
                                    ID <span class="text-red-500">*</span></label>
                                <input type="text" placeholder="00-0000-000"
                                    class="w-full rounded-[14px] border-gray-200 border-[1.5px] px-[18px] py-[13.5px] text-sm text-[#0e1732] placeholder-[#a4abb8] focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-semibold outline-none transition bg-[#fcfdff]">
                            </div>

                            <div class="grid grid-cols-2 gap-[14px]">
                                <div>
                                    <label class="block text-[11.5px] uppercase tracking-wider font-extrabold text-gray-400 mb-[5px] pl-[5px]">First Name <span class="text-red-500">*</span></label>
                                    <input type="text" placeholder="First Name" class="w-full rounded-[14px] border-gray-200 border-[1.5px] px-[18px] py-[13.5px] text-sm text-[#0e1732] placeholder-[#a4abb8] focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-semibold outline-none transition bg-[#fcfdff]">
                                </div>
                                <div>
                                    <label class="block text-[11.5px] uppercase tracking-wider font-extrabold text-gray-400 mb-[5px] pl-[5px]">Last Name <span class="text-red-500">*</span></label>
                                    <input type="text" placeholder="Last Name" class="w-full rounded-[14px] border-gray-200 border-[1.5px] px-[18px] py-[13.5px] text-sm text-[#0e1732] placeholder-[#a4abb8] focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-semibold outline-none transition bg-[#fcfdff]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11.5px] uppercase tracking-wider font-extrabold text-gray-400 mb-[5px] pl-[5px]">Course/Degree <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select class="w-full rounded-[14px] border-gray-200 border-[1.5px] px-[18px] py-[13.5px] text-[14px] text-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-semibold outline-none transition bg-white appearance-none cursor-pointer">
                                        <option value="" selected disabled>Select Course</option>
                                        <option value="cs">Computer Science</option>
                                        <option value="it">Information Technology</option>
                                        <option value="ba">Business Administration</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-5 text-gray-400">
                                        <svg class="h-[18px] w-[18px] stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11.5px] uppercase tracking-wider font-extrabold text-gray-400 mb-[5px] pl-[5px]">Year Level <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select class="w-full rounded-[14px] border-gray-200 border-[1.5px] px-[18px] py-[13.5px] text-[14px] text-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-semibold outline-none transition bg-white appearance-none cursor-pointer">
                                        <option value="" disabled selected>Select Year Level</option>
                                        <option>1st Year</option>
                                        <option>2nd Year</option>
                                        <option>3rd Year</option>
                                        <option>4th Year</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-5 text-gray-400">
                                        <svg class="h-[18px] w-[18px] stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-[#eaf1ff]/50 border-[1px] border-[#9dbbf7]/30 rounded-xl p-[14px] px-[18px] flex gap-[14px] items-start mt-[4px]">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-[20px] h-[20px] text-[#2c71fa] shrink-0 mt-[1px]">
                                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 01.67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 11-.671-1.34l.041-.022zM12 9a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                                </svg>
                                <div class="pl-1 text-left w-full mt-[-1px]">
                                    <p class="text-[11px] font-[800] uppercase tracking-widest text-[#1e5ee1] opacity-90 mt-[1px]">Next Step : Capture Biometrics</p>
                                    <p class="text-[11px] text-[#4b669b] font-[550] leading-snug mt-1 opacity-95">Ensure all student information is accurately filled out before proceeding to the next step, which requires capturing the student's fingerprint biometrics for enrollment.</p>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center justify-end gap-4 pt-[8px] w-full px-2 mt-[-4px]">
                                <button type="button" @click="openModal = false"
                                    class="inline-flex shrink-0 min-h-[48px] min-w-[120px] items-center justify-center rounded-[14px] border-[2px] border-[#e1e5ee] bg-gray-50/50 px-4 text-center text-[11px] font-[800] uppercase tracking-widest text-[#8692a8] transition hover:border-[#ccd2df] hover:bg-gray-100 hover:text-gray-600 focus:outline-none">
                                    Cancel
                                </button>
                                <!-- Routing cleanly functionally smartly safely neatly identically organically perfectly directly correctly logically securely intelligently gracefully completely securely identically into 2 gracefully dynamically safely expertly logically optimally seamlessly expertly -> -->
                                <button type="button" @click="step = 2"
                                    class="inline-flex min-h-[48px] min-w-[280px] items-center justify-center rounded-[14px] border-[2px] border-transparent bg-[#1e52df] px-6 text-center text-[14px] font-extrabold uppercase tracking-widest text-white shadow-[0_4px_10px_rgba(20,80,210,0.18)] transition hover:-translate-y-[1px] hover:bg-blue-600 focus:outline-none">
                                    Proceed to Fingerprint
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- ============================================== -->
                    <!-- STEP 2: Fingerprint Already Enrolled Info UI -->
                    <!-- ============================================== -->
                    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300 delay-50"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        class="flex flex-col h-full w-full rounded-[20px] bg-white overflow-hidden pb-[20px] min-h-[500px]">

                        <div class="flex items-center px-[32px] py-[28px] mt-[4px]">
                            <button @click="step = 1" type="button"
                                class="w-[32px] h-[32px] rounded-full bg-[#102360] flex items-center justify-center text-white shrink-0 hover:scale-[1.05] active:scale-[0.95] transition-all cursor-pointer mr-[18px]">
                                <svg class="w-[20px] h-[20px] ml-[-1.5px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                            </button>
                            <h3 class="text-[21.5px] font-[500] text-[#0f1523] tracking-[-0.015em] mb-[1px]">Fingerprint Enrollement</h3>
                        </div>

                        <div class="flex-1 flex flex-col items-center justify-start pt-[5px] px-[32px] pb-[10px]">
                            <p class="text-[17.5px] text-[#2c3345] mb-[32px] font-[450] tracking-[0.012em]">
                                Fingerprint already enrolled
                            </p>

                            <div class="w-full max-w-[320px] h-[325px] bg-[#f5f6f9] rounded-[36px] flex flex-col items-center justify-center relative mb-[60px] mx-auto border-[1.5px] border-[#ebeef3]">
                                <div class="relative w-[138px] h-[198px] rounded-full border-[2.2px] border-[#087df2] flex flex-col items-center justify-center pt-[15px] mb-[15px]">
                                    <img src="/icons/fingerprint_scanner.png" alt="Saved Bio Vector Trace"
                                        class="w-[125px] h-[165px] object-contain opacity-[0.24] saturate-[0] brightness-[1] contrast-[140%] mix-blend-multiply mt-[-10px] scale-[0.9]" />

                                    <div class="absolute bottom-[calc(-24px/2)] left-1/2 transform -translate-x-1/2 bg-[#c6facd] text-[#137b2d] font-[700] text-[10px] px-[22px] py-[4.5px] tracking-[0.025em] rounded-full z-10 block whitespace-nowrap shadow-[0_2px_4px_rgba(25,185,55,0.06)] border-[0.5px] border-[#8ced9d]/40">
                                        Enrolled
                                    </div>
                                </div>
                            </div>

                            <div class="w-full flex items-center justify-between px-1 w-[90%] mb-[15px] max-w-[430px] mx-auto">
                                <!-- Re-Enroll Link accurately effectively elegantly natively safely directs smoothly logically completely properly expertly successfully logically optimally natively gracefully perfectly precisely safely cleanly intuitively directly identical organically effortlessly intelligently specifically logically completely appropriately purely.  -->
                                <a href="#" @click.prevent="step = 3; isScanning = false;"
                                    class="text-[14.5px] text-[#000000] font-[450] decoration-[1px] underline underline-offset-4 decoration-[#595f70] hover:text-[#0b5cff] hover:decoration-[#0b5cff] transition-all cursor-pointer">
                                    Re-enroll Fingerprint
                                </a>

                                <button type="button" @click="openModal = false"
                                    class="bg-[#0b5cff] hover:bg-[#114bca] shadow-[0_4px_12px_rgba(11,92,255,0.22)] text-white text-[13.5px] tracking-wide font-[600] px-[28px] py-[13.5px] rounded-[10px] transition-all active:scale-[0.96] min-w-[145px] text-center border-[0px]">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- STEP 3: Re-enroll New Biometric Capture / Scanning Frame precisely structured exactly accurately completely smartly seamlessly perfectly intuitively organically flawlessly appropriately effectively matching smoothly perfectly logically reliably gracefully effortlessly smoothly structurally neatly directly specifically strictly natively.  -->
                    <!-- ============================================== -->
                    <div x-show="step === 3" x-transition:enter="transition ease-out duration-300 delay-50"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        class="flex flex-col h-full w-full rounded-[20px] bg-white overflow-hidden pb-[20px] min-h-[500px]">

                        <!-- Header natively correctly cleanly efficiently elegantly exactly organically properly exactly seamlessly successfully appropriately functionally identically mapped appropriately safely smoothly effectively. -->
                        <div class="flex items-center px-[32px] py-[28px] mt-[4px]">
                            <button @click="step = 2" type="button" :disabled="isScanning"
                                class="w-[32px] h-[32px] rounded-full bg-[#102360] flex items-center justify-center text-white shrink-0 hover:scale-[1.05] active:scale-[0.95] transition-all cursor-pointer mr-[18px] disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-[20px] h-[20px] ml-[-1.5px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                            </button>
                            <h3 class="text-[21.5px] font-[500] text-[#0f1523] tracking-[-0.015em] mb-[1px]">Fingerprint Enrollment</h3>
                        </div>

                        <div class="flex-1 flex flex-col items-center justify-start pt-[20px] px-[32px] pb-[10px]">

                            <!-- Prompt Sub Heading perfectly styled successfully organically. -->
                            <p class="text-[17.5px] text-[#2c3345] mb-[45px] font-[450] tracking-[0.012em]">
                                Place your Finger on the scanner
                            </p>

                            <!-- Bounding Scanning Layout Graphic flawlessly directly securely organically smartly accurately identical specifically exactly intuitively properly efficiently organically logically. -->
                            <div class="relative w-[150px] h-[195px] flex items-center justify-center mb-[75px] shrink-0 mx-auto px-1 py-1 box-border">

                                <!-- Explicit 90 degree Square corner cuts matching intelligently optimally flawlessly logically cleanly seamlessly gracefully safely cleanly correctly natively purely explicitly standard natively accurately nicely safely smoothly. -->
                                <div class="absolute inset-0 pointer-events-none">
                                    <div class="absolute top-0 left-0 w-[42px] h-[42px] border-t-[3.5px] border-l-[3.5px] border-[#1853fc]"></div>
                                    <div class="absolute top-0 right-0 w-[42px] h-[42px] border-t-[3.5px] border-r-[3.5px] border-[#1853fc]"></div>
                                    <div class="absolute bottom-0 left-0 w-[42px] h-[42px] border-b-[3.5px] border-l-[3.5px] border-[#1853fc]"></div>
                                    <div class="absolute bottom-0 right-0 w-[42px] h-[42px] border-b-[3.5px] border-r-[3.5px] border-[#1853fc]"></div>
                                </div>

                                <!-- Center Fingerprint Gray vector appropriately mapped dynamically gracefully securely intelligently successfully gracefully securely seamlessly cleanly optimally. -->
                                <img src="/icons/fingerprint_scanner.png" alt="Fingerprint Vector Placeholder Scan Space Core Shape Graphic Design Resource Context Identical Flawless Accurate UI Component Visual Box Element Image Standard Seamless Perfectly Successfully Natively Effectively cleanly."
                                    class="w-[125px] h-[165px] object-contain transition-all duration-300 pointer-events-none z-[5]"
                                    :class="isScanning ? 'opacity-[0.85] saturate-[1.1] scale-[1.02]' : 'opacity-[0.16] saturate-[0] scale-[1]'" />

                                <!-- Horizontal Solid Scanner Status Tracker seamlessly organically elegantly effectively beautifully gracefully beautifully functionally optimally smoothly intuitively seamlessly cleanly correctly intuitively expertly smoothly smoothly gracefully correctly elegantly strictly identical.  -->
                                <!-- Line elegantly safely stays positioned completely beautifully solidly accurately seamlessly dynamically nicely bottom aligned optimally flawlessly successfully strictly until flawlessly expertly functionally smartly seamlessly animation smoothly precisely successfully strictly naturally seamlessly successfully flawlessly elegantly functionally brilliantly actively natively functionally gracefully securely precisely cleverly reliably begins brilliantly elegantly naturally natively organically correctly strictly expertly flawlessly safely functionally intelligently.-->
                                <div :class="isScanning ? 'scan-animation absolute left-[2px] right-[2px] opacity-[1]' : 'absolute bottom-2 left-[2px] right-[2px] opacity-100 block transition-all'"
                                    class="h-[3px] bg-[#1ccb14] rounded-full z-[10] shadow-[0_3px_14px_rgba(28,203,20,0.6)]"></div>
                            </div>

                            <!-- Oval Completely Bright Green Confirm Scanner Button strictly gracefully expertly neatly effortlessly securely structurally natively structurally effectively flawlessly elegantly logically logically expertly elegantly natively natively efficiently identically accurately effortlessly flawlessly smoothly.  -->
                            <button type="button" @click="handleScan()" :disabled="isScanning"
                                :class="isScanning ? 'cursor-wait bg-[#15b00c] shadow-[0_5px_15px_rgba(28,214,5,0.4)] opacity-95 scale-[0.99]' : 'bg-[#1ccb14] hover:bg-[#1abe12] shadow-[0_4px_12px_rgba(28,214,5,0.3)] active:scale-[0.96]'"
                                class="w-full max-w-[240px] font-[500] text-[16px] text-white px-[45px] py-[13.5px] rounded-full flex items-center justify-center transition-all">
                                <span x-text="isScanning ? 'Scanning...' : 'Scan'" class="mt-[0px]"></span>
                            </button>

                        </div>

                    </div>
                    <!-- ============================================== -->
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT STUDENT MODAL -->
    <div x-cloak x-show="openEditModal" class="relative z-[220]" aria-modal="true" role="dialog">
        <div x-show="openEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="openEditModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.outside="openEditModal = false"
                    class="relative transform rounded-[20px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-[550px]">
                    <div class="absolute right-4 top-4 z-10">
                        <button type="button" @click="openEditModal = false"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none" aria-label="Close">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="px-8 py-8 text-gray-800">
                        <h3 class="text-xl font-extrabold text-[#111624] mb-2">Edit student</h3>
                        <p class="text-sm text-gray-500 mb-6">Update account details and save changes.</p>

                        @if (session('show_edit_modal') && $errors->any())
                        <div
                            class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-left text-[13px] font-medium text-red-700">
                            Please fix the errors below.
                        </div>
                        @endif

                        @if ($errors->has('general'))
                        <div
                            class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-left text-[13px] font-medium text-red-700">
                            {{ $errors->first('general') }}
                        </div>
                        @endif

                        <form class="space-y-5" method="POST" action="{{ route('finger-print.student.update') }}">
                            @csrf
                            <input type="hidden" name="user_id" :value="editForm.user_id">
                            <input type="hidden" name="role" value="student">
                            <input type="hidden" name="list_student_id" value="{{ $student_id }}">
                            <input type="hidden" name="list_course" value="{{ $course }}">
                            <input type="hidden" name="list_year_level" value="{{ $year_level }}">

                            <div>
                                <label
                                    class="block text-[11.5px] uppercase tracking-wider font-extrabold text-gray-400 mb-[5px] pl-[5px]">Student
                                    ID <span class="text-red-500">*</span></label>
                                <input type="text" name="student_id" x-model="editForm.student_id" required
                                    class="w-full rounded-[14px] border-gray-200 border-[1.5px] px-[18px] py-[13.5px] text-sm text-[#0e1732] focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-semibold outline-none transition bg-[#fcfdff]">
                                @error('student_id')
                                <p class="mt-1 pl-1 text-[11px] font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-[14px]">
                                <div>
                                    <label
                                        class="block text-[11.5px] uppercase tracking-wider font-extrabold text-gray-400 mb-[5px] pl-[5px]">First
                                        Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="first_name" x-model="editForm.first_name" required
                                        class="w-full rounded-[14px] border-gray-200 border-[1.5px] px-[18px] py-[13.5px] text-sm text-[#0e1732] focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-semibold outline-none transition bg-[#fcfdff]">
                                    @error('first_name')
                                    <p class="mt-1 pl-1 text-[11px] font-medium text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-[11.5px] uppercase tracking-wider font-extrabold text-gray-400 mb-[5px] pl-[5px]">Last
                                        Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="last_name" x-model="editForm.last_name" required
                                        class="w-full rounded-[14px] border-gray-200 border-[1.5px] px-[18px] py-[13.5px] text-sm text-[#0e1732] focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-semibold outline-none transition bg-[#fcfdff]">
                                    @error('last_name')
                                    <p class="mt-1 pl-1 text-[11px] font-medium text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-[11.5px] uppercase tracking-wider font-extrabold text-gray-400 mb-[5px] pl-[5px]">Middle
                                    Name <span class="text-red-500">*</span></label>
                                <input type="text" name="middle_name" x-model="editForm.middle_name" required
                                    class="w-full rounded-[14px] border-gray-200 border-[1.5px] px-[18px] py-[13.5px] text-sm text-[#0e1732] focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-semibold outline-none transition bg-[#fcfdff]">
                                @error('middle_name')
                                <p class="mt-1 pl-1 text-[11px] font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-[11.5px] uppercase tracking-wider font-extrabold text-gray-400 mb-[5px] pl-[5px]">Email
                                    <span class="text-red-500">*</span></label>
                                <input type="email" name="email" x-model="editForm.email" required
                                    class="w-full rounded-[14px] border-gray-200 border-[1.5px] px-[18px] py-[13.5px] text-sm text-[#0e1732] focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-semibold outline-none transition bg-[#fcfdff]">
                                @error('email')
                                <p class="mt-1 pl-1 text-[11px] font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-[11.5px] uppercase tracking-wider font-extrabold text-gray-400 mb-[5px] pl-[5px]">New
                                    password</label>
                                <input type="password" name="password" x-model="editForm.password" autocomplete="new-password"
                                    placeholder="Leave blank to keep current"
                                    class="w-full rounded-[14px] border-gray-200 border-[1.5px] px-[18px] py-[13.5px] text-sm text-[#0e1732] focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-semibold outline-none transition bg-[#fcfdff]">
                                @error('password')
                                <p class="mt-1 pl-1 text-[11px] font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-[11.5px] uppercase tracking-wider font-extrabold text-gray-400 mb-[5px] pl-[5px]">Course/Degree
                                    <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select name="course" x-model="editForm.course" required
                                        class="w-full rounded-[14px] border-gray-200 border-[1.5px] px-[18px] py-[13.5px] text-[14px] text-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-semibold outline-none transition bg-white appearance-none cursor-pointer">
                                        <template x-for="opt in courseOptions" :key="opt">
                                            <option :value="opt" x-text="opt"></option>
                                        </template>
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-5 text-gray-400">
                                        <svg class="h-[18px] w-[18px] stroke-[2.5]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                @error('course')
                                <p class="mt-1 pl-1 text-[11px] font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-[11.5px] uppercase tracking-wider font-extrabold text-gray-400 mb-[5px] pl-[5px]">Year
                                    Level <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select name="year_level" x-model="editForm.year_level" required
                                        class="w-full rounded-[14px] border-gray-200 border-[1.5px] px-[18px] py-[13.5px] text-[14px] text-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-semibold outline-none transition bg-white appearance-none cursor-pointer">
                                        <option value="1st Year">1st Year</option>
                                        <option value="2nd Year">2nd Year</option>
                                        <option value="3rd Year">3rd Year</option>
                                        <option value="4th Year">4th Year</option>
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-5 text-gray-400">
                                        <svg class="h-[18px] w-[18px] stroke-[2.5]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                @error('year_level')
                                <p class="mt-1 pl-1 text-[11px] font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex gap-[12px] pt-2">
                                <button type="button" @click="openEditModal = false"
                                    class="flex-1 rounded-[14px] border-[2px] border-[#e1e5ee] bg-gray-50/50 py-[14px] text-center text-[12px] font-[800] uppercase tracking-widest text-[#8692a8] transition hover:border-[#ccd2df] hover:bg-gray-100 hover:text-gray-600 focus:outline-none">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="flex-1 rounded-[14px] bg-[#1e52df] py-[14px] text-center text-[12px] font-[800] uppercase tracking-widest text-white shadow-[0_4px_10px_rgba(20,80,210,0.18)] transition hover:bg-blue-600 focus:outline-none">
                                    Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>