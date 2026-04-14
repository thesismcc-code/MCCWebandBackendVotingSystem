<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Eligibility - Fingerprint Voting System</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #102864;
        }

        .bg-main-panel {
            background-color: #0C3189;
        }

        [x-cloak] {
            display: none !important;
        }

        .input-ring {
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .eligibility-pagination .text-gray-700,
        .eligibility-pagination .text-gray-600,
        .eligibility-pagination .text-gray-800 {
            color: rgba(255, 255, 255, 0.92) !important;
        }

        .eligibility-pagination a[class*="border-gray"],
        .eligibility-pagination span[class*="border-gray"] {
            border-color: rgba(255, 255, 255, 0.35) !important;
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: #fff !important;
        }

        .eligibility-pagination span[class*="cursor-not-allowed"] {
            opacity: 0.55;
        }
    </style>
</head>

<body x-data="{
    openModal: false,
    selectedStudent: null,
    parseStudent(el) {
        const raw = el.getAttribute('data-student');
        this.selectedStudent = raw ? JSON.parse(raw) : null;
        this.openModal = true;
    },
    closeModal() {
        this.openModal = false;
        this.selectedStudent = null;
    }
}"
    class="p-4 md:p-6 min-h-screen text-white flex flex-col font-sans relative overflow-x-hidden">

    <!-- HEADER SECTION -->
    <div class="max-w-7xl mx-auto w-full mb-5 flex items-center justify-between px-2 mt-4 md:mt-2">
        <div class="flex items-center gap-4">
            <a href="{{ route('view.quick-access') }}"
                class="bg-white text-[#102864] rounded-full w-10 h-10 flex items-center justify-center hover:scale-105 transition-transform shadow-md shrink-0">
                <svg class="w-5 h-5 ml-[2px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M14 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl md:text-[28px] font-bold tracking-tight text-white leading-tight">Student Eligibility</h1>
                <p class="text-blue-100 text-sm font-medium mt-1 tracking-wide opacity-95">Verify if a student is allowed to vote (email verified).</p>
            </div>
        </div>
    </div>

    <!-- MAIN BLUE CONTAINER -->
    <div class="max-w-7xl mx-auto w-full bg-main-panel rounded-3xl p-6 relative flex-1 flex flex-col mb-4 border border-white/5">

        <!-- Top Overview Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-6 mb-7">

            <div class="bg-white rounded-[20px] shadow-sm py-[18px] px-5 flex items-center gap-5 cursor-default hover:-translate-y-1 transition-transform">
                <div class="bg-blue-600 rounded-xl w-[60px] h-[60px] flex items-center justify-center shrink-0 ml-1">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <div class="text-[32px] font-[800] text-gray-900 leading-[1.2]">{{ number_format($data['counts']['total']) }}</div>
                    <div class="text-[14.5px] text-gray-700 font-medium tracking-wide leading-tight">Total Students</div>
                </div>
            </div>

            <div class="bg-white rounded-[20px] shadow-sm py-[18px] px-5 flex items-center gap-5 cursor-default hover:-translate-y-1 transition-transform">
                <div class="bg-[#22c55e] rounded-xl w-[60px] h-[60px] flex items-center justify-center shrink-0 ml-1">
                    <svg class="w-[34px] h-[34px] text-white" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <div class="text-[32px] font-[800] text-gray-900 leading-[1.2]">{{ number_format($data['counts']['eligible']) }}</div>
                    <div class="text-[14.5px] text-gray-700 font-medium tracking-wide leading-tight">Eligible</div>
                </div>
            </div>

            <div class="bg-white rounded-[20px] shadow-sm py-[18px] px-5 flex items-center gap-5 cursor-default hover:-translate-y-1 transition-transform">
                <div class="bg-[#ef4444] rounded-xl w-[60px] h-[60px] flex items-center justify-center shrink-0 ml-1">
                    <svg class="w-[28px] h-[28px] text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <div class="text-[32px] font-[800] text-gray-900 leading-[1.2]">{{ number_format($data['counts']['not_eligible']) }}</div>
                    <div class="text-[14.5px] text-gray-700 font-medium tracking-wide leading-tight">Not Eligible</div>
                </div>
            </div>

        </div>

        <form method="get" action="{{ route('view.student-eligibility') }}"
            class="flex flex-col md:flex-row gap-5 mb-5 items-stretch md:h-[48px]">
            <div class="relative flex-1 min-h-[48px]">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-[22px] w-[22px] text-gray-200 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" name="student_id" value="{{ $student_id }}"
                    placeholder="Search by Student ID or Name"
                    class="w-full h-full min-h-[48px] bg-transparent input-ring focus:ring-1 focus:ring-blue-400 outline-none text-white placeholder-blue-50/90 rounded-lg block pl-12 pr-3 text-[15.5px] shadow-sm transition-all tracking-wide">
            </div>

            <div class="w-full md:w-96 relative shrink-0 min-h-[48px]">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                    <svg class="h-[22px] w-[22px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                    </svg>
                </div>
                <select name="course" onchange="this.form.submit()"
                    class="w-full h-full min-h-[48px] bg-transparent input-ring focus:ring-1 focus:ring-blue-400 rounded-lg block pl-12 pr-10 text-white text-[15px] font-medium appearance-none cursor-pointer outline-none hover:bg-white/5">
                    <option class="text-gray-900" value="">All Courses</option>
                    @foreach ($data['courses'] as $courseOption)
                        <option class="text-gray-900" value="{{ $courseOption }}" @selected($course === $courseOption)>
                            {{ $courseOption }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="w-[20px] h-[20px] opacity-90" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
            </div>

            <button type="submit" class="md:hidden min-h-[48px] rounded-lg bg-white/10 input-ring text-[15px] font-semibold">
                Apply filters
            </button>
            <button type="submit" class="hidden"></button>
        </form>

        <!-- White Data List Container -->
        <div class="bg-white rounded-xl shadow overflow-hidden flex-1 relative flex flex-col min-h-0 text-[14.5px]">
            <div class="w-full overflow-x-auto h-full">
                <table class="w-full text-left whitespace-nowrap min-w-max border-collapse font-medium">
                    <thead class="border-b-[2px] border-gray-900 bg-white sticky top-0 z-10">
                        <tr>
                            <th class="pl-7 pr-4 py-4 text-black font-[800] w-36">Student ID</th>
                            <th class="px-5 py-4 text-black font-[800] w-52">Name</th>
                            <th class="px-5 py-4 text-black font-[800] w-64">Courses</th>
                            <th class="px-5 py-4 text-black font-[800] w-48">Email</th>
                            <th class="px-5 py-4 text-black font-[800] w-32">Status</th>
                            <th class="px-7 py-4 text-black font-[800] text-center w-28">
                                <span class="flex items-center justify-center gap-[5px]">
                                    <svg class="w-4 h-4 mb-px opacity-90" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    View
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-[#1f2937]">
                        @forelse ($data['students'] as $student)
                            @php
                                $isEligible = filled($student->getEmailVerifiedAt());
                                $fullName = collect([$student->getFirstName(), $student->getMiddleName(), $student->getLastName()])->filter()->join(' ');
                                $verifiedAt = $student->getEmailVerifiedAt();
                                $verifiedFormatted = $verifiedAt
                                    ? \Illuminate\Support\Carbon::parse($verifiedAt)->timezone(config('app.timezone'))->format('M j, Y g:i A')
                                    : '—';
                                $modalPayload = [
                                    'full_name' => $fullName,
                                    'student_id' => $student->getStudentId() ?? '—',
                                    'course' => $student->getCourse() ?? '—',
                                    'year_level' => $student->getYearLevel() ?? '—',
                                    'email' => $student->getEmail(),
                                    'email_verified_label' => $isEligible ? 'Verified' : 'Not Verified',
                                    'verified_on' => $verifiedFormatted,
                                    'eligible' => $isEligible,
                                    'fingerprint_status' => 'Not on file',
                                ];
                            @endphp
                            <tr class="border-b border-gray-200 hover:bg-gray-50/70 transition-colors">
                                <td class="pl-7 pr-4 py-3.5 text-gray-700 tracking-wide">{{ $student->getStudentId() ?? '—' }}</td>
                                <td class="px-5 py-3.5 font-semibold text-gray-900">{{ $fullName }}</td>
                                <td class="px-5 py-3.5 text-gray-700">{{ $student->getCourse() ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-gray-700 max-w-[14rem] truncate" title="{{ $student->getEmail() }}">{{ $student->getEmail() }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        @if ($isEligible)
                                            <div class="w-2.5 h-2.5 rounded-full bg-[#22c55e]"></div>
                                            <span class="text-gray-900 tracking-wide font-[550]">Eligible</span>
                                        @else
                                            <div class="w-2.5 h-2.5 rounded-full bg-[#ef4444]"></div>
                                            <span class="text-gray-900 tracking-wide font-[550]">Not Eligible</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-7 py-3.5 text-center">
                                    <button type="button" data-student='@json($modalPayload)'
                                        @click="parseStudent($event.currentTarget)"
                                        class="px-4 py-[6px] bg-[#dbeafe] text-[#1d4ed8] rounded-full font-bold inline-flex items-center justify-center gap-1.5 hover:bg-blue-200 transition">
                                        <svg class="w-3.5 h-3.5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                        <span class="text-[12px] pt-[0.5px]">View</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-7 py-10 text-center text-gray-600">No students match your filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="eligibility-pagination mt-6 mb-1 pt-2 shrink-0 w-full text-[15px] text-white">
            {{ $data['students']->withQueryString()->links('pagination::tailwind') }}
        </div>

    </div>

    <div x-cloak x-show="openModal"
        class="fixed inset-0 z-[100] bg-[#111827]/60 flex items-center justify-center w-full min-h-full px-4 overflow-y-auto py-8 shadow-sm backdrop-blur-sm transition-all"
        @click.self="closeModal()">

        <div
            class="bg-white rounded-2xl shadow-2xl overflow-hidden text-gray-900 w-full max-w-[500px] flex flex-col font-sans shrink-0 border border-gray-100 relative mt-auto md:mt-12 transition transform">

            <div class="flex justify-between items-center pl-6 pr-5 py-[22px] border-b-[2px] border-blue-900 mt-2 rounded">
                <h2 class="text-2xl font-[800] tracking-[-0.03em] font-sans">Student Details</h2>
                <button type="button" @click="closeModal()"
                    class="text-gray-950 opacity-90 hover:opacity-100 hover:text-red-500 transition-colors -mt-[2px] cursor-pointer bg-transparent border-0 font-extrabold pb-0">
                    <svg class="w-6 h-6 shrink-0 mt-[2px] stroke-[3.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="px-6 py-7 pb-9 space-y-[15px] max-w-none text-[15px] font-medium" x-show="selectedStudent">
                <div class="grid grid-cols-[155px_minmax(0,1fr)] items-center">
                    <label class="text-black font-semibold tracking-wide">Name:</label>
                    <input type="text" disabled class="w-full bg-[#f4f5f8] rounded-[5px] px-3.5 py-[7.5px] font-[600] text-[#374151] border-none outline-none text-[14px]"
                        :value="selectedStudent?.full_name ?? ''">
                </div>

                <div class="grid grid-cols-[155px_minmax(0,1fr)] items-center">
                    <label class="text-black font-semibold tracking-wide">Student ID:</label>
                    <input type="text" readonly class="w-full bg-[#f4f5f8] rounded-[5px] px-3.5 py-[7.5px] font-[600] text-[#374151] border-none outline-none text-[14px]"
                        :value="selectedStudent?.student_id ?? ''">
                </div>

                <div class="grid grid-cols-[155px_minmax(0,1fr)] items-center">
                    <label class="text-black font-semibold tracking-wide">Course:</label>
                    <input type="text" readonly class="w-full bg-[#f4f5f8] rounded-[5px] px-3.5 py-[7.5px] font-[600] text-[#374151] border-none outline-none text-[14px]"
                        :value="selectedStudent?.course ?? ''">
                </div>

                <div class="grid grid-cols-[155px_minmax(0,1fr)] items-center">
                    <label class="text-black font-semibold tracking-wide">Year level:</label>
                    <input type="text" disabled class="w-full bg-[#f4f5f8] rounded-[5px] px-3.5 py-[7.5px] font-[600] text-[#374151] border-none outline-none text-[14px]"
                        :value="selectedStudent?.year_level ?? ''">
                </div>

                <div class="grid grid-cols-[155px_minmax(0,1fr)] items-center">
                    <label class="text-black font-semibold tracking-wide mt-1 leading-tight">Fingerprint<br>Status:</label>
                    <input type="text" disabled class="w-full bg-[#f4f5f8] rounded-[5px] px-3.5 py-[7.5px] font-[600] text-[#374151] border-none outline-none text-[14px]"
                        :value="selectedStudent?.fingerprint_status ?? 'Not on file'">
                </div>

                <div class="grid grid-cols-[155px_minmax(0,1fr)] items-center mt-3">
                    <label class="text-black font-semibold tracking-wide">Email:</label>
                    <input type="text" disabled class="w-full bg-[#f4f5f8] rounded-[5px] px-3.5 py-[7.5px] font-[600] text-[#374151] border-none outline-none text-[14px]"
                        :value="selectedStudent?.email ?? ''">
                </div>

                <div class="grid grid-cols-[155px_minmax(0,1fr)] items-center">
                    <label class="text-black font-semibold tracking-wide leading-tight">Email<br>Verification:</label>
                    <input type="text" disabled class="w-full bg-[#f4f5f8] rounded-[5px] px-3.5 py-[7.5px] font-[600] text-[#374151] border-none outline-none text-[14px]"
                        :value="selectedStudent?.email_verified_label ?? ''">
                </div>

                <div class="grid grid-cols-[155px_minmax(0,1fr)] items-center">
                    <label class="text-black font-semibold tracking-wide">Verified On:</label>
                    <input type="text" disabled class="w-full bg-[#f4f5f8] rounded-[5px] px-3.5 py-[7.5px] font-[600] text-[#374151] border-none outline-none text-[14px]"
                        :value="selectedStudent?.verified_on ?? '—'">
                </div>

                <div class="grid grid-cols-[155px_minmax(0,1fr)] items-center mt-3">
                    <label class="text-black font-semibold tracking-wide mt-1 leading-tight">Voting<br>Eligibility:</label>
                    <div class="w-full bg-[#f4f5f8] rounded-[5px] px-3.5 py-[7px] text-[14.5px] font-[600] text-gray-800 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full inline-block shadow-sm shrink-0"
                            :class="selectedStudent?.eligible ? 'bg-[#22c55e] ring-2 ring-green-100' : 'bg-[#ef4444] ring-2 ring-red-100'"></span>
                        <span x-text="selectedStudent?.eligible ? 'Eligible' : 'Not Eligible'"></span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
