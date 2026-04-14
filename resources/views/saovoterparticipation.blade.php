<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAO Voter Participation Report</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #102864;
        }

        .bg-main-panel {
            background-color: #0C3189;
        }

        .highlight-row {
            box-shadow: inset 0 0 0 2px #289bf5;
        }
    </style>
</head>

<body class="p-4 md:p-6 min-h-screen text-white flex flex-col">
    <div class="max-w-7xl mx-auto w-full mb-5 flex items-center justify-between px-2 mt-4 md:mt-2">
        <div class="flex items-center gap-4">
            <a href="{{ route('view.sao-dashboard') }}"
                class="bg-white text-[#113285] rounded-full w-10 h-10 flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                title="Go Back">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white leading-tight">Voter Participation</h1>
                <p class="text-blue-200 text-[11px] font-medium mt-0.5">Voting Attendance Report</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto w-full bg-main-panel rounded-3xl p-6 md:p-10 shadow-2xl flex-1 flex flex-col mb-4">
        <form action="{{ request()->url() }}" method="GET" class="grid grid-cols-1 lg:grid-cols-12 gap-3 mb-4">
            <div class="lg:col-span-5">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-white/90">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" oninput="this.form.submit()"
                        placeholder="Search by Student ID or Name"
                        class="w-full py-[10px] pl-10 pr-4 rounded-[10px] border border-white/80 bg-[#163fa9] text-[13.5px] font-medium text-white placeholder:text-white/70 focus:outline-none focus:ring-2 focus:ring-white/40">
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-white/90">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422M12 14v7"></path>
                        </svg>
                    </span>
                    <select name="course_filter" onchange="this.form.submit()"
                        class="w-full py-[10px] pl-10 pr-10 rounded-[10px] border border-white/80 bg-[#163fa9] text-[13.5px] font-medium text-white focus:outline-none focus:ring-2 focus:ring-white/40 appearance-none">
                        <option value="" class="text-black">All Courses</option>
                        @foreach (($courses ?? []) as $course)
                            <option value="{{ $course }}" {{ request('course_filter') === $course ? 'selected' : '' }}>
                                {{ $course }}
                            </option>
                        @endforeach
                    </select>
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </span>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-white/90">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </span>
                    <select name="year_filter" onchange="this.form.submit()"
                        class="w-full py-[10px] pl-10 pr-10 rounded-[10px] border border-white/80 bg-[#163fa9] text-[13.5px] font-medium text-white focus:outline-none focus:ring-2 focus:ring-white/40 appearance-none">
                        <option value="" class="text-black">Year Level</option>
                        <option value="1" {{ request('year_filter') == '1' ? 'selected' : '' }}>1st Year</option>
                        <option value="2" {{ request('year_filter') == '2' ? 'selected' : '' }}>2nd Year</option>
                        <option value="3" {{ request('year_filter') == '3' ? 'selected' : '' }}>3rd Year</option>
                        <option value="4" {{ request('year_filter') == '4' ? 'selected' : '' }}>4th Year</option>
                    </select>
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </span>
                </div>
            </div>
        </form>

        <div class="bg-white rounded-2xl overflow-hidden shadow-xl flex-1 relative z-10 flex flex-col mb-4">
            <div class="overflow-x-auto w-full max-h-full pb-[20px]">
                <table class="w-full text-left border-collapse min-w-[960px]">
                    <thead>
                        <tr class="border-b-2 border-gray-100 bg-white text-left sticky top-0 z-30">
                            <th class="pl-[32px] pr-6 py-5 text-[15px] font-bold text-[#0c0d16] leading-tight">Student ID</th>
                            <th class="px-6 py-5 text-[15px] font-bold text-[#0c0d16] leading-tight">Name</th>
                            <th class="px-6 py-5 text-[15px] font-bold text-[#0c0d16] leading-tight">Course</th>
                            <th class="px-6 py-5 text-[15px] font-bold text-[#0c0d16] leading-tight">Year Level</th>
                            <th class="px-6 py-5 text-[15px] font-bold text-[#0c0d16] leading-tight">Date & Time</th>
                            <th class="pl-6 pr-[32px] py-5 text-[15px] font-bold text-[#0c0d16] text-center leading-tight">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100/70 bg-white">
                        @if (isset($voters) && count($voters) > 0)
                            @foreach ($voters as $voter)
                                <tr class="hover:bg-blue-50/30 transition-colors {{ isset($voter->is_highlighted) && $voter->is_highlighted ? 'highlight-row' : '' }}">
                                    <td class="pl-[32px] pr-6 py-[20px] text-[14.5px] font-medium text-[#2d3043]">{{ $voter->student_id }}</td>
                                    <td class="px-6 py-[20px] text-[14.5px] font-medium text-[#2d3043]">{{ $voter->name }}</td>
                                    <td class="px-6 py-[20px] text-[14.5px] text-[#2d3043]">{{ $voter->course_name }}</td>
                                    <td class="px-6 py-[20px] text-[14.5px] text-[#2d3043]">{{ $voter->year_level }}</td>
                                    <td class="px-6 py-[20px] text-[14.5px] text-[#2d3043]">
                                        {{ $voter->voted_at ? \Carbon\Carbon::parse($voter->voted_at)->format('d-m-Y g:iA') : 'N/A' }}
                                    </td>
                                    <td class="pl-6 pr-[32px] py-[20px] text-center">
                                        <span class="inline-flex items-center rounded-full bg-green-100 text-green-700 text-[11px] font-bold px-4 py-1.5 tracking-wide">
                                            {{ $voter->status ?? 'Voted' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-[14px] font-medium text-slate-500">
                                    No voter participation records found.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-center items-center mt-1">
            @if (isset($voters) && $voters instanceof \Illuminate\Pagination\LengthAwarePaginator && $voters->hasPages())
                <div class="flex items-center gap-2">
                    @if ($voters->onFirstPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/10 text-white/50 cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </span>
                    @else
                        <a href="{{ $voters->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/15 text-white hover:bg-white hover:text-[#0C3189] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                    @endif

                    @php
                        $current = $voters->currentPage();
                        $last = $voters->lastPage();
                        $start = max(1, min($current - 2, $last - 4));
                        $end = min($last, $start + 4);
                    @endphp

                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page === $current)
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-white text-[#0C3189] text-[13px] font-bold">{{ $page }}</span>
                        @else
                            <a href="{{ $voters->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/15 text-white text-[13px] font-medium hover:bg-white hover:text-[#0C3189] transition-colors">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($voters->hasMorePages())
                        <a href="{{ $voters->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/15 text-white hover:bg-white hover:text-[#0C3189] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @else
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/10 text-white/50 cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    @endif
                </div>
            @else
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-white text-[#0C3189] text-[13px] font-bold">1</span>
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/15 text-white text-[13px] font-medium">2</span>
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/15 text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            @endif
        </div>
    </div>
</body>

</html>
