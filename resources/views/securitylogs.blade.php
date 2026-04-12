<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Logs - Fingerprint Voting System</title>

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

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body x-data="{
    searchQuery: '{{ request('search', '') }}',
    selectedCourse: '{{ request('course', '') }}',
    selectedYearLevel: '{{ request('year_level', '') }}'
}" class="p-4 md:p-6 min-h-screen text-white flex flex-col font-sans">

    <!-- HEADER SECTION -->
    <div class="max-w-7xl mx-auto w-full mb-5 flex items-center justify-between px-2 mt-4 md:mt-2">
        <div class="flex items-center gap-4">
            <a href="{{ route('view.quick-access') }}"
                class="bg-white text-[#113285] rounded-full w-10 h-10 flex items-center justify-center hover:scale-110 transition-transform shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white leading-tight">Security Logs</h1>
                <p class="text-blue-200 text-[11px] font-medium mt-0.5">Monitor Security Events</p>
            </div>
        </div>
    </div>

    <!-- MAIN BLUE CONTAINER -->
    <div
        class="max-w-7xl mx-auto w-full bg-main-panel rounded-3xl p-6 md:p-10 relative shadow-2xl flex-1 flex flex-col mb-4 overflow-hidden">

        <!-- STATS CARDS -->
        <div class="flex flex-wrap gap-5 mb-6 md:max-w-5xl relative z-10">

            <!-- 1. Duplicate Vote Attempts -->
            <div
                class="bg-white rounded-[20px] p-5 py-4 flex items-center gap-4 shadow-sm flex-1 min-w-[240px] border-2 border-[#1853fc]/30">
                <div
                    class="bg-blue-600 w-[52px] h-[52px] rounded-[16px] flex items-center justify-center text-white shrink-0 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                            d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                    </svg>
                </div>
                <div>
                    <div class="text-[32px] font-bold text-gray-900 leading-tight">{{ $counts['duplicate_votes'] ?? 4 }}
                    </div>
                    <div class="text-[12px] text-gray-500 font-semibold tracking-wide">Duplicate Vote Attempts</div>
                </div>
            </div>

            <!-- 2. Rejected Fingerprint Scans -->
            <div class="bg-white rounded-[20px] p-5 py-4 flex items-center gap-4 shadow-sm flex-1 min-w-[240px]">
                <div
                    class="bg-[#e53e3e] w-[52px] h-[52px] rounded-[16px] flex items-center justify-center text-white shrink-0 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                            d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                    </svg>
                </div>
                <div>
                    <div class="text-[32px] font-bold text-gray-900 leading-tight">
                        {{ $counts['rejected_fingerprints'] ?? 2 }}</div>
                    <div class="text-[12px] text-gray-500 font-semibold tracking-wide">Rejected Fingerprint Scans</div>
                </div>
            </div>

            <!-- 3. Denied Access Attempts -->
            <div class="bg-white rounded-[20px] p-5 py-4 flex items-center gap-4 shadow-sm flex-1 min-w-[240px]">
                <div
                    class="bg-[#ffd93d] w-[52px] h-[52px] rounded-[16px] flex items-center justify-center text-white shrink-0 shadow-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
                <div>
                    <div class="text-[32px] font-bold text-gray-900 leading-tight">{{ $counts['denied_access'] ?? 1 }}
                    </div>
                    <div class="text-[12px] text-gray-500 font-semibold tracking-wide">Denied Access Attempts</div>
                </div>
            </div>
        </div>

        <!-- FILTERS ROW -->
        <div class="flex flex-wrap gap-3 relative z-10 w-full mb-4">

            <!-- Search -->
            <div class="relative flex-1 min-w-[240px]">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-white/70">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                    </svg>
                </div>
                <input type="text" x-model="searchQuery" placeholder="Search by Student ID or Name"
                    @keydown.enter="applyFilters()"
                    class="block w-full py-[10px] pl-[44px] pr-4 rounded-[10px] border border-white/80 bg-[#163fa9] focus:outline-none focus:ring-2 focus:ring-white/40 text-[13.5px] font-medium text-white placeholder-white/60 shadow-sm hover:bg-white/10 transition-colors">
            </div>

            <!-- Course Filter -->
            <div class="relative w-[220px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-white/90">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <select x-model="selectedCourse" onchange="applyFilters()"
                    class="block w-full py-[10px] pl-[38px] pr-10 rounded-[10px] border border-white/80 bg-[#163fa9] focus:outline-none focus:ring-2 focus:ring-white/40 appearance-none text-[13.5px] font-medium text-white shadow-sm hover:bg-white/10 transition-colors cursor-pointer">
                    <option value="" class="text-black">All Courses</option>
                    @foreach ($courses ?? [] as $course)
                        <option value="{{ $course }}" class="text-black"
                            {{ request('course') === $course ? 'selected' : '' }}>
                            {{ $course }}
                        </option>
                    @endforeach
                    {{-- Fallback static options if no dynamic data --}}
                    @if (empty($courses))
                        <option value="Computer Science" class="text-black">Computer Science</option>
                        <option value="Information Technology" class="text-black">Information Technology</option>
                        <option value="Business Administration" class="text-black">Business Administration</option>
                    @endif
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-white z-20">
                    <svg class="w-[18px] h-[18px] stroke-[2.5]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>

            <!-- Year Level Filter -->
            <div class="relative w-[190px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-white/90">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <select x-model="selectedYearLevel" onchange="applyFilters()"
                    class="block w-full py-[10px] pl-[38px] pr-10 rounded-[10px] border border-white/80 bg-[#163fa9] focus:outline-none focus:ring-2 focus:ring-white/40 appearance-none text-[13.5px] font-medium text-white shadow-sm hover:bg-white/10 transition-colors cursor-pointer">
                    <option value="" class="text-black">Year Level</option>
                    <option value="1st Year" class="text-black"
                        {{ request('year_level') === '1st Year' ? 'selected' : '' }}>1st Year</option>
                    <option value="2nd Year" class="text-black"
                        {{ request('year_level') === '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                    <option value="3rd Year" class="text-black"
                        {{ request('year_level') === '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                    <option value="4th Year" class="text-black"
                        {{ request('year_level') === '4th Year' ? 'selected' : '' }}>4th Year</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-white z-20">
                    <svg class="w-[18px] h-[18px] stroke-[2.5]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- TABLE SECTION -->
        <div
            class="bg-white rounded-2xl overflow-hidden shadow-xl flex-1 relative z-10 flex flex-col mb-4 max-h-[100%]">
            <div class="overflow-x-auto w-full max-h-full pb-[35px] hide-scrollbar rounded-b-2xl">
                <table class="w-full text-left border-collapse min-w-[900px] mb-8">
                    <thead>
                        <tr
                            class="border-b-2 border-gray-100 bg-white shadow-[0px_2px_8px_rgba(0,0,0,0.015)] text-left sticky top-0 z-30">
                            <th class="pl-[42px] pr-6 py-5 text-[15px] font-bold text-[#0c0d16] leading-tight">Student
                                ID</th>
                            <th class="px-6 py-5 text-[15px] font-bold text-[#0c0d16] leading-tight">Name</th>
                            <th class="px-6 py-5 text-[15px] font-bold text-[#0c0d16] leading-tight">Course</th>
                            <th class="px-6 py-5 text-[15px] font-bold text-[#0c0d16] leading-tight">Year Level</th>
                            <th class="px-6 py-5 text-[15px] font-bold text-[#0c0d16] leading-tight">First Attempt</th>
                            <th class="px-6 py-5 text-[15px] font-bold text-[#0c0d16] leading-tight">Second Attempt
                            </th>
                            <th class="pl-6 pr-[42px] py-5 text-[15px] font-bold text-[#0c0d16] text-center">Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100/60 bg-white overflow-y-auto">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-blue-50/20 transition-colors group"
                                x-show="
                                    (searchQuery === '' || '{{ strtolower($log->getStudentID() ?? '') }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower(($log->getFirstName() ?? '') . ' ' . ($log->getLastName() ?? '')) }}'.includes(searchQuery.toLowerCase()))
&& (selectedCourse === '' || '{{ $log->getCourse() ?? '' }}' === selectedCourse)
                                    && (selectedYearLevel === '' || '{{ $log->getYearLevel() ?? '' }}' === selectedYearLevel)
                                ">
                                <td class="pl-[42px] pr-6 py-[22px] text-[14.5px] font-medium text-[#292c3a]">
                                    {{ $log->getStudentID() ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-[22px]">
                                    <div class="flex items-center gap-[14px]">
                                        <div
                                            class="w-[32px] h-[32px] rounded-full bg-[#1e4cd6] flex items-center justify-center text-white shadow-[0_2px_6px_rgba(30,76,214,0.3)] shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <span class="text-[14.5px] font-medium tracking-[-0.015em] text-[#292c3a]">
                                            {{ ($log->getFirstName() ?? '') . ' ' . ($log->getLastName() ?? '') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-[22px] text-[14.5px] text-[#2d3043]">
                                    {{ $log->getCourse() ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-[22px] text-[14.5px] text-[#2d3043]">
                                    {{ $log->getYearLevel() ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-[22px] text-[14px] font-medium text-[#44465b]">
                                    {{ $log->getFirstAttempt() ? \Carbon\Carbon::parse($log->getFirstAttempt())->format('m-d-Y g:iA') : 'N/A' }}
                                </td>
                                <td class="px-6 py-[22px] text-[14px] font-medium text-[#44465b]">
                                    {{ $log->getSecondAttempt() ? \Carbon\Carbon::parse($log->getSecondAttempt())->format('m-d-Y g:iA') : 'N/A' }}
                                </td>
                                <td class="pl-6 pr-[42px] py-[22px] text-center">
                                    @php $status = strtolower($log->getStatus() ?? 'blocked'); @endphp
                                    @if ($status === 'blocked')
                                        <span
                                            class="bg-[#e53e3e] text-white text-[11px] tracking-wide font-bold px-[16px] py-[6px] rounded-full inline-flex items-center">Blocked</span>
                                    @elseif ($status === 'allowed')
                                        <span
                                            class="bg-[#24b93b] text-white text-[11px] tracking-wide font-bold px-[16px] py-[6px] rounded-full inline-flex items-center">Allowed</span>
                                    @else
                                        <span
                                            class="bg-gray-200 text-gray-600 text-[11px] tracking-wide font-bold px-[16px] py-[6px] rounded-full inline-flex items-center">{{ ucfirst($status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-16 text-gray-400 font-medium">No security
                                    logs
                                    found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if (isset($logs) && method_exists($logs, 'lastPage') && $logs->lastPage() > 1)
                    <div
                        class="flex items-center justify-between px-[42px] py-4 border-t border-gray-100 bg-white sticky bottom-0">
                        <p class="text-[13px] text-gray-400 font-medium">
                            Showing {{ $logs->firstItem() }}–{{ $logs->lastItem() }} of {{ $logs->total() }} logs
                        </p>
                        <div class="flex items-center gap-2">
                            {{-- Previous --}}
                            @if ($logs->onFirstPage())
                                <span
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-300 cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $logs->previousPageUrl() }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-blue-600 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                            @endif

                            @php
                                $current = $logs->currentPage();
                                $last = $logs->lastPage();
                                $start = max(1, min($current - 2, $last - 4));
                                $end = min($last, $start + 4);
                            @endphp

                            @if ($start > 1)
                                <a href="{{ $logs->url(1) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-500 text-[13px] font-medium hover:bg-blue-50 hover:text-blue-600 transition-all">1</a>
                                @if ($start > 2)
                                    <span
                                        class="w-8 h-8 flex items-center justify-center text-gray-400 text-[13px]">...</span>
                                @endif
                            @endif

                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $current)
                                    <span
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#1853fc] text-white text-[13px] font-bold shadow">{{ $page }}</span>
                                @else
                                    <a href="{{ $logs->url($page) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-500 text-[13px] font-medium hover:bg-blue-50 hover:text-blue-600 transition-all">{{ $page }}</a>
                                @endif
                            @endfor

                            @if ($end < $last)
                                @if ($end < $last - 1)
                                    <span
                                        class="w-8 h-8 flex items-center justify-center text-gray-400 text-[13px]">...</span>
                                @endif
                                <a href="{{ $logs->url($last) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-500 text-[13px] font-medium hover:bg-blue-50 hover:text-blue-600 transition-all">{{ $last }}</a>
                            @endif

                            {{-- Next --}}
                            @if ($logs->hasMorePages())
                                <a href="{{ $logs->nextPageUrl() }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-blue-600 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @else
                                <span
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-300 cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <script>
        function applyFilters() {
            const search = document.querySelector('[x-model="searchQuery"]')?.value ?? '';
            const course = document.querySelector('[x-model="selectedCourse"]')?.value ?? '';
            const yearLevel = document.querySelector('[x-model="selectedYearLevel"]')?.value ?? '';
            const url = new URL(window.location.href);

            if (search) url.searchParams.set('search', search);
            else url.searchParams.delete('search');

            if (course) url.searchParams.set('course', course);
            else url.searchParams.delete('course');

            if (yearLevel) url.searchParams.set('year_level', yearLevel);
            else url.searchParams.delete('year_level');

            // Reset to page 1 when filters change
            url.searchParams.delete('page');

            window.location.href = url.toString();
        }
    </script>

</body>

</html>
