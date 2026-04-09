<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics - Fingerprint Voting System</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #102864; }
        .bg-main-panel { background-color: #0C3189; }
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    </style>
</head>

<body class="p-4 md:p-6 min-h-screen text-white flex flex-col font-sans">

    <!-- HEADER SECTION -->
    <div class="max-w-7xl mx-auto w-full mb-5 flex items-center justify-between px-2 mt-4 md:mt-2">
        <div class="flex items-center gap-4">
            <a href="{{ route('view.quick-access') }}"
                class="bg-white text-[#113285] rounded-full w-10 h-10 flex items-center justify-center hover:scale-110 transition-transform shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white leading-tight">Reports & Analytics</h1>
                <p class="text-blue-200 text-[11px] font-medium mt-0.5">Real-time summary, statistics, and results</p>
            </div>
        </div>

        <!-- End of Election Reports Button -->
        <a href="{{ route('view.reports-and-analytics-end-of-election') }}"
            class="bg-[#0b64f9] hover:bg-blue-600 hover:-translate-y-0.5 text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-[0_4px_14px_rgba(11,100,249,0.4)] transition-all duration-200">
            End of Election Reports
        </a>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="max-w-7xl mx-auto w-full bg-main-panel rounded-3xl p-6 md:p-10 relative shadow-2xl flex-1 flex flex-col mb-4 overflow-hidden">

        <!-- STATS CARDS ROW -->
        <div class="flex flex-wrap gap-4 mb-8 relative z-10">

            <!-- Total Registered Voters -->
            <div class="bg-white rounded-[20px] p-5 py-4 flex items-center gap-4 shadow-sm flex-1 min-w-[180px]">
                <div class="bg-blue-600 w-[52px] h-[52px] rounded-[16px] flex items-center justify-center text-white shrink-0 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                    </svg>
                </div>
                <div>
                    <div class="text-[28px] font-bold text-gray-900 leading-tight">150</div>
                    <div class="text-[11px] text-gray-500 font-semibold tracking-wide">Registered Voters</div>
                </div>
            </div>

            <!-- Total Votes Cast -->
            <div class="bg-white rounded-[20px] p-5 py-4 flex items-center gap-4 shadow-sm flex-1 min-w-[180px]">
                <div class="bg-[#24b93b] w-[52px] h-[52px] rounded-[16px] flex items-center justify-center text-white shrink-0 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-[28px] font-bold text-gray-900 leading-tight">150</div>
                    <div class="text-[11px] text-gray-500 font-semibold tracking-wide">Votes Cast</div>
                </div>
            </div>

            <!-- Total Positions -->
            <div class="bg-white rounded-[20px] p-5 py-4 flex items-center gap-4 shadow-sm flex-1 min-w-[180px]">
                <div class="bg-[#7c3aed] w-[52px] h-[52px] rounded-[16px] flex items-center justify-center text-white shrink-0 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <div class="text-[28px] font-bold text-gray-900 leading-tight">3</div>
                    <div class="text-[11px] text-gray-500 font-semibold tracking-wide">Total Positions</div>
                </div>
            </div>

            <!-- Total Candidates -->
            <div class="bg-white rounded-[20px] p-5 py-4 flex items-center gap-4 shadow-sm flex-1 min-w-[180px]">
                <div class="bg-[#f59e0b] w-[52px] h-[52px] rounded-[16px] flex items-center justify-center text-white shrink-0 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-[28px] font-bold text-gray-900 leading-tight">19</div>
                    <div class="text-[11px] text-gray-500 font-semibold tracking-wide">Total Candidates</div>
                </div>
            </div>

            <!-- Voter Turnout -->
            <div class="bg-white rounded-[20px] p-5 py-4 flex items-center gap-4 shadow-sm flex-1 min-w-[180px]">
                <div class="bg-[#0ea5e9] w-[52px] h-[52px] rounded-[16px] flex items-center justify-center text-white shrink-0 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-[28px] font-bold text-gray-900 leading-tight">80%</div>
                    <div class="text-[11px] text-gray-500 font-semibold tracking-wide">Voter Turnout</div>
                </div>
            </div>

        </div>

        <!-- CHARTS ROW -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-5 relative z-10">

            <!-- LEFT: REAL TIME VOTER TURNOUT -->
            <div class="md:col-span-2 bg-white rounded-2xl p-6 shadow-sm">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-5">Real Time Voter Turnout</p>

                <div class="grid grid-cols-2 gap-x-6 gap-y-6">
                    <!-- Total Voters -->
                    <div>
                        <p class="text-[12px] text-gray-400 font-semibold mb-1">Total Voters</p>
                        <p class="text-[30px] font-bold text-gray-900 leading-none">100</p>
                    </div>
                    <!-- Turnout -->
                    <div>
                        <p class="text-[12px] text-gray-400 font-semibold mb-1">Turnout</p>
                        <p class="text-[30px] font-bold text-[#0b64f9] leading-none">10%</p>
                    </div>
                    <!-- Voted -->
                    <div>
                        <p class="text-[12px] text-gray-400 font-semibold mb-1">Voted</p>
                        <p class="text-[30px] font-bold text-[#24b93b] leading-none">53</p>
                    </div>
                    <!-- Not Yet -->
                    <div>
                        <p class="text-[12px] text-gray-400 font-semibold mb-1">Not Yet Voted</p>
                        <p class="text-[30px] font-bold text-gray-400 leading-none">47</p>
                    </div>
                </div>

                <!-- Visual donut-style indicator -->
                <div class="mt-6 pt-5 border-t border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[12px] text-gray-400 font-semibold">Overall Progress</span>
                        <span class="text-[12px] font-bold text-gray-700">53 / 100</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-[10px] overflow-hidden">
                        <div class="bg-[#0b64f9] h-full rounded-full transition-all duration-700" style="width: 53%"></div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: PER YEAR LEVEL TURNOUT -->
            <div class="md:col-span-3 bg-white rounded-2xl p-6 shadow-sm">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-5">Per Year Level Turnout</p>

                <div class="flex flex-col gap-5">

                    <!-- 1st Year -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[13.5px] font-semibold text-gray-700">1st Year</span>
                            <span class="text-[13px] font-bold text-gray-900">80%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-[10px] overflow-hidden">
                            <div class="bg-[#0b64f9] h-full rounded-full" style="width: 80%"></div>
                        </div>
                    </div>

                    <!-- 2nd Year -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[13.5px] font-semibold text-gray-700">2nd Year</span>
                            <span class="text-[13px] font-bold text-gray-900">67%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-[10px] overflow-hidden">
                            <div class="bg-[#24b93b] h-full rounded-full" style="width: 67%"></div>
                        </div>
                    </div>

                    <!-- 3rd Year -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[13.5px] font-semibold text-gray-700">3rd Year</span>
                            <span class="text-[13px] font-bold text-gray-900">43%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-[10px] overflow-hidden">
                            <div class="bg-[#f59e0b] h-full rounded-full" style="width: 43%"></div>
                        </div>
                    </div>

                    <!-- 4th Year -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[13.5px] font-semibold text-gray-700">4th Year</span>
                            <span class="text-[13px] font-bold text-gray-900">31%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-[10px] overflow-hidden">
                            <div class="bg-[#ef4444] h-full rounded-full" style="width: 31%"></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

</body>
</html>
