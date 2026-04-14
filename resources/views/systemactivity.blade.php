<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Activity - Fingerprint Voting System</title>

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

<body x-data="{
    activeTab: 'realtime'
}" class="p-4 md:p-6 min-h-screen text-white flex flex-col font-sans">

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
                <h1 class="text-2xl font-bold tracking-tight text-white leading-tight">System Activity</h1>
                <p class="text-blue-200 text-[11px] font-medium mt-0.5">Real-time monitoring of system usage and security events</p>
            </div>
        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="max-w-7xl mx-auto w-full bg-main-panel rounded-3xl p-6 md:p-10 relative shadow-2xl flex-1 flex flex-col mb-4 overflow-hidden">

        <!-- TOGGLE BUTTONS -->
        <div class="flex gap-3 mb-6">
            <button
                @click="activeTab = 'realtime'"
                :class="activeTab === 'realtime'
                    ? 'bg-[#0066FF] text-white shadow-lg shadow-blue-900/40'
                    : 'bg-white text-gray-900 hover:bg-gray-100'"
                class="px-6 py-2 rounded-lg font-semibold text-sm transition-all">
                Real Time Logs
            </button>
            <button
                @click="activeTab = 'error'"
                :class="activeTab === 'error'
                    ? 'bg-[#0066FF] text-white shadow-lg shadow-blue-900/40'
                    : 'bg-white text-gray-900 hover:bg-gray-100'"
                class="px-6 py-2 rounded-lg font-semibold text-sm transition-all">
                Error Logs
            </button>
        </div>

        <!-- FILTER ROW -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">

            <!-- Dynamic Title -->
            <h2 class="text-2xl font-normal text-white">
                <span x-show="activeTab === 'realtime'">Real Time Logs</span>
                <span x-show="activeTab === 'error'" x-cloak>Error Logs</span>
            </h2>

            <!-- Filters -->
            <div class="flex gap-3">
                <!-- User Filter -->
                <div class="relative w-[200px]">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-white/90">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <select class="block w-full py-[10px] pl-[38px] pr-10 rounded-[10px] border border-white/80 bg-[#163fa9] focus:outline-none focus:ring-2 focus:ring-white/40 appearance-none text-[13.5px] font-medium text-white shadow-sm hover:bg-white/10 transition-colors cursor-pointer">
                        <option value="" class="text-black">All Users</option>
                        <option value="admin" class="text-black">Admin</option>
                        <option value="student" class="text-black">Student</option>
                        <option value="comelec" class="text-black">Comelec</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-white z-20">
                        <svg class="w-[18px] h-[18px] stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                <!-- Date Filter -->
                <div class="relative w-[200px]">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-white/90">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <select class="block w-full py-[10px] pl-[38px] pr-10 rounded-[10px] border border-white/80 bg-[#163fa9] focus:outline-none focus:ring-2 focus:ring-white/40 appearance-none text-[13.5px] font-medium text-white shadow-sm hover:bg-white/10 transition-colors cursor-pointer">
                        <option value="" class="text-black">All Dates</option>
                        <option value="today" class="text-black">Today</option>
                        <option value="yesterday" class="text-black">Yesterday</option>
                        <option value="last_week" class="text-black">Last Week</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-white z-20">
                        <svg class="w-[18px] h-[18px] stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLE SECTION -->
        <div class="bg-white rounded-2xl overflow-hidden shadow-xl flex-1 relative z-10 flex flex-col mb-4" style="min-height: 0;">
            <div class="overflow-x-auto w-full flex-1 rounded-b-2xl" style="overflow-y: auto; min-height: 0;">

                <!-- REAL TIME LOGS TABLE -->
                <div x-show="activeTab === 'realtime'">
                <table class="w-full text-left border-collapse min-w-[880px] mb-0">
                    <thead>
                        <tr class="border-b-2 border-gray-100 bg-white shadow-[0px_2px_8px_rgba(0,0,0,0.015)] sticky top-0 z-30">
                            <th class="pl-[42px] pr-6 py-5 text-[15px] font-bold text-[#0c0d16] text-center w-[12%]">Date</th>
                            <th class="px-6 py-5 text-[15px] font-bold text-[#0c0d16] w-[12%]">Time</th>
                            <th class="px-6 py-5 text-[15px] font-bold text-[#0c0d16] w-[12%]">User</th>
                            <th class="px-6 py-5 text-[15px] font-bold text-[#0c0d16] w-[10%]">Auth</th>
                            <th class="px-6 py-5 text-[15px] font-bold text-[#0c0d16]">Activity</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100/60 bg-white">
                        @forelse ($realtimeLogs as $log)
                            @php
                                $roleKey = strtolower($log->getRole() !== '' ? $log->getRole() : 'guest');
                                $roleClass = match ($roleKey) {
                                    'student' => 'bg-[#d2e2fa] text-[#4f6492] text-[11px] tracking-wide font-bold px-[18px] py-[6px] rounded-full border-[0.5px] border-[#adc7f6]/40 inline-flex items-center',
                                    'admin' => 'bg-gray-100 text-gray-500 text-[11px] tracking-wide font-bold px-[18px] py-[6px] rounded-full inline-flex items-center',
                                    'comelec' => 'bg-[#fee173] text-[#4f4316] text-[10px] tracking-wide font-bold px-[18px] py-[6px] rounded-full border border-yellow-300 inline-flex items-center',
                                    'sao' => 'bg-purple-100 text-purple-800 text-[11px] tracking-wide font-bold px-[18px] py-[6px] rounded-full inline-flex items-center',
                                    default => 'bg-gray-100 text-gray-600 text-[11px] tracking-wide font-bold px-[18px] py-[6px] rounded-full inline-flex items-center',
                                };
                                try {
                                    $dt = \Illuminate\Support\Carbon::parse($log->getCreatedAt());
                                    $dateStr = $dt->format('m-d-Y');
                                    $timeStr = $dt->format('h:i:s A');
                                } catch (\Throwable) {
                                    $dateStr = '—';
                                    $timeStr = '—';
                                }
                                $authCh = strtolower($log->getAuthChannel() !== '' ? $log->getAuthChannel() : 'guest');
                                $authLabel = match ($authCh) {
                                    'session' => 'Session',
                                    'jwt' => 'JWT',
                                    'web' => 'Web',
                                    default => 'Guest',
                                };
                                $authClass = match ($authCh) {
                                    'session' => 'bg-emerald-100 text-emerald-800 text-[10px] tracking-wide font-bold px-[12px] py-[6px] rounded-full inline-flex items-center',
                                    'jwt' => 'bg-sky-100 text-sky-800 text-[10px] tracking-wide font-bold px-[12px] py-[6px] rounded-full inline-flex items-center',
                                    'web' => 'bg-slate-200 text-slate-700 text-[10px] tracking-wide font-bold px-[12px] py-[6px] rounded-full inline-flex items-center',
                                    default => 'bg-gray-100 text-gray-600 text-[10px] tracking-wide font-bold px-[12px] py-[6px] rounded-full inline-flex items-center',
                                };
                            @endphp
                            <tr class="hover:bg-blue-50/20 transition-colors">
                                <td class="pl-[42px] pr-6 py-[22px] text-center text-[14.5px] font-medium text-[#44465b]">{{ $dateStr }}</td>
                                <td class="px-6 py-[22px] text-[14.5px] font-medium text-[#44465b]">{{ $timeStr }}</td>
                                <td class="px-6 py-[22px]">
                                    <span class="{{ $roleClass }}">{{ ucfirst($roleKey) }}</span>
                                </td>
                                <td class="px-6 py-[22px]">
                                    <span class="{{ $authClass }}">{{ $authLabel }}</span>
                                </td>
                                <td class="px-6 py-[22px] text-[14.5px] text-[#2d3043]">{{ $log->getActivity() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center text-gray-400 font-medium text-sm">No real-time logs yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>

                <!-- ERROR LOGS TABLE -->
                <div x-show="activeTab === 'error'" x-cloak>
                <table class="w-full text-left border-collapse min-w-[880px] mb-0">
                    <thead>
                        <tr class="border-b-2 border-gray-100 bg-white shadow-[0px_2px_8px_rgba(0,0,0,0.015)] sticky top-0 z-30">
                            <th class="pl-[42px] pr-6 py-5 text-[15px] font-bold text-[#0c0d16] text-center w-[12%]">Date</th>
                            <th class="px-6 py-5 text-[15px] font-bold text-[#0c0d16] w-[12%]">Time</th>
                            <th class="px-6 py-5 text-[15px] font-bold text-[#0c0d16] w-[12%]">User</th>
                            <th class="px-6 py-5 text-[15px] font-bold text-[#0c0d16] w-[10%]">Auth</th>
                            <th class="px-6 py-5 text-[15px] font-bold text-[#0c0d16]">Activity</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100/60 bg-white">
                        @forelse ($errorLogs as $log)
                            @php
                                $roleKey = strtolower($log->getRole() !== '' ? $log->getRole() : 'guest');
                                $roleClass = match ($roleKey) {
                                    'student' => 'bg-[#d2e2fa] text-[#4f6492] text-[11px] tracking-wide font-bold px-[18px] py-[6px] rounded-full border-[0.5px] border-[#adc7f6]/40 inline-flex items-center',
                                    'admin' => 'bg-gray-100 text-gray-500 text-[11px] tracking-wide font-bold px-[18px] py-[6px] rounded-full inline-flex items-center',
                                    'comelec' => 'bg-[#fee173] text-[#4f4316] text-[10px] tracking-wide font-bold px-[18px] py-[6px] rounded-full border border-yellow-300 inline-flex items-center',
                                    'sao' => 'bg-purple-100 text-purple-800 text-[11px] tracking-wide font-bold px-[18px] py-[6px] rounded-full inline-flex items-center',
                                    default => 'bg-gray-100 text-gray-600 text-[11px] tracking-wide font-bold px-[18px] py-[6px] rounded-full inline-flex items-center',
                                };
                                try {
                                    $dt = \Illuminate\Support\Carbon::parse($log->getCreatedAt());
                                    $dateStr = $dt->format('m-d-Y');
                                    $timeStr = $dt->format('h:i:s A');
                                } catch (\Throwable) {
                                    $dateStr = '—';
                                    $timeStr = '—';
                                }
                                $rowClass = $log->getLevel() === 'error' || $log->getLevel() === 'critical'
                                    ? 'text-red-500 font-semibold'
                                    : 'text-amber-600 font-semibold';
                                $authCh = strtolower($log->getAuthChannel() !== '' ? $log->getAuthChannel() : 'guest');
                                $authLabel = match ($authCh) {
                                    'session' => 'Session',
                                    'jwt' => 'JWT',
                                    'web' => 'Web',
                                    default => 'Guest',
                                };
                                $authClass = match ($authCh) {
                                    'session' => 'bg-emerald-100 text-emerald-800 text-[10px] tracking-wide font-bold px-[12px] py-[6px] rounded-full inline-flex items-center',
                                    'jwt' => 'bg-sky-100 text-sky-800 text-[10px] tracking-wide font-bold px-[12px] py-[6px] rounded-full inline-flex items-center',
                                    'web' => 'bg-slate-200 text-slate-700 text-[10px] tracking-wide font-bold px-[12px] py-[6px] rounded-full inline-flex items-center',
                                    default => 'bg-gray-100 text-gray-600 text-[10px] tracking-wide font-bold px-[12px] py-[6px] rounded-full inline-flex items-center',
                                };
                            @endphp
                            <tr class="hover:bg-red-50/20 transition-colors">
                                <td class="pl-[42px] pr-6 py-[22px] text-center text-[14.5px] font-medium text-[#44465b]">{{ $dateStr }}</td>
                                <td class="px-6 py-[22px] text-[14.5px] font-medium text-[#44465b]">{{ $timeStr }}</td>
                                <td class="px-6 py-[22px]">
                                    <span class="{{ $roleClass }}">{{ ucfirst($roleKey) }}</span>
                                </td>
                                <td class="px-6 py-[22px]">
                                    <span class="{{ $authClass }}">{{ $authLabel }}</span>
                                </td>
                                <td class="px-6 py-[22px] text-[14.5px] {{ $rowClass }}">{{ $log->getActivity() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center text-gray-400 font-medium text-sm">No error or warning logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>

            </div>

            <!-- SUMMARY -->
            <div class="flex items-center justify-between px-[42px] py-4 border-t border-gray-100 bg-white sticky bottom-0">
                <p class="text-[13px] text-gray-400 font-medium">
                    Real-time (2xx): {{ $realtimeLogs->total() }} total
                    · Warnings / errors (4xx/5xx): {{ $errorLogs->total() }} total
                    <span class="text-gray-300">· {{ $realtimeLogs->perPage() }} per page · Up to 500 most recent stored</span>
                </p>
            </div>
        </div>

        <!-- PAGINATION (Real Time) — same pattern as votinglogs.blade.php -->
        @if ($realtimeLogs->lastPage() > 1)
        <div x-show="activeTab === 'realtime'" class="mt-4 flex justify-center items-center gap-2">
                @if ($realtimeLogs->onFirstPage())
                    <span class="w-8 h-8 rounded border border-white/20 text-white/30 flex items-center justify-center cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $realtimeLogs->previousPageUrl() }}" class="w-8 h-8 rounded border border-white/30 text-white flex items-center justify-center hover:bg-white/10 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                @endif
                @php
                    $rtCurrent = $realtimeLogs->currentPage();
                    $rtLast = $realtimeLogs->lastPage();
                    $rtStart = max(1, min($rtCurrent - 2, $rtLast - 4));
                    $rtEnd = min($rtLast, $rtStart + 4);
                @endphp
                @for ($page = $rtStart; $page <= $rtEnd; $page++)
                    @if ($page == $rtCurrent)
                        <span class="w-8 h-8 rounded bg-white text-[#0C3189] font-bold text-sm shadow flex items-center justify-center">{{ $page }}</span>
                    @else
                        <a href="{{ $realtimeLogs->url($page) }}" class="w-8 h-8 rounded border border-white/30 text-white font-medium text-sm flex items-center justify-center hover:bg-white/10 transition-colors">{{ $page }}</a>
                    @endif
                @endfor
                @if ($realtimeLogs->hasMorePages())
                    <a href="{{ $realtimeLogs->nextPageUrl() }}" class="w-8 h-8 rounded border border-white/30 text-white flex items-center justify-center hover:bg-white/10 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                @else
                    <span class="w-8 h-8 rounded border border-white/20 text-white/30 flex items-center justify-center cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </span>
                @endif
        </div>
        @endif

        <!-- PAGINATION (Error logs) -->
        @if ($errorLogs->lastPage() > 1)
        <div x-show="activeTab === 'error'" x-cloak class="mt-4 flex justify-center items-center gap-2">
                @if ($errorLogs->onFirstPage())
                    <span class="w-8 h-8 rounded border border-white/20 text-white/30 flex items-center justify-center cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $errorLogs->previousPageUrl() }}" class="w-8 h-8 rounded border border-white/30 text-white flex items-center justify-center hover:bg-white/10 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                @endif
                @php
                    $errCurrent = $errorLogs->currentPage();
                    $errLast = $errorLogs->lastPage();
                    $errStart = max(1, min($errCurrent - 2, $errLast - 4));
                    $errEnd = min($errLast, $errStart + 4);
                @endphp
                @for ($page = $errStart; $page <= $errEnd; $page++)
                    @if ($page == $errCurrent)
                        <span class="w-8 h-8 rounded bg-white text-[#0C3189] font-bold text-sm shadow flex items-center justify-center">{{ $page }}</span>
                    @else
                        <a href="{{ $errorLogs->url($page) }}" class="w-8 h-8 rounded border border-white/30 text-white font-medium text-sm flex items-center justify-center hover:bg-white/10 transition-colors">{{ $page }}</a>
                    @endif
                @endfor
                @if ($errorLogs->hasMorePages())
                    <a href="{{ $errorLogs->nextPageUrl() }}" class="w-8 h-8 rounded border border-white/30 text-white flex items-center justify-center hover:bg-white/10 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                @else
                    <span class="w-8 h-8 rounded border border-white/20 text-white/30 flex items-center justify-center cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </span>
                @endif
        </div>
        @endif

    </div>

</body>
</html>
