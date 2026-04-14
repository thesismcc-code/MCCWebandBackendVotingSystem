<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics - Fingerprint Voting System</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #102864; }
        .bg-main-panel { background-color: #0C3189; }
        .report-progress-track { min-width: 0; }
        .report-progress-fill {
            height: 100%;
            min-width: 0;
            max-width: 100%;
            border-radius: 9999px;
            transition: width 0.7s ease;
        }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    </style>
</head>

<body
    class="p-4 md:p-6 min-h-screen text-white flex flex-col font-sans"
    data-report-live-url="{{ route('reports.live-data') }}"
    data-report-poll-ms="5000"
>

    @php
        $stats = $data['stats_card_data'];
        $rt = $data['realtime_turnout'];
        $totalStudents = (int) ($rt['total_students'] ?? 0);
        $turnoutPct = (float) ($rt['turnout_percent'] ?? 0);
        $barWidth = $totalStudents > 0 ? min(100, max(0, $turnoutPct)) : 0;
        $yearBarColors = ['#0b64f9', '#24b93b', '#f59e0b', '#ef4444'];
    @endphp

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
                <p id="report-election-line" class="text-blue-100/90 text-[11px] font-semibold mt-1 {{ empty($data['election']['name'] ?? null) ? 'hidden' : '' }}">
                    Active election: <span id="report-election-name">{{ $data['election']['name'] ?? '' }}</span>
                </p>
                <p id="report-live-status" class="text-emerald-300/90 text-[10px] font-medium mt-1 flex items-center gap-1.5">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse" aria-hidden="true"></span>
                    <span id="report-live-updated">Live — updating every few seconds</span>
                </p>
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

            <!-- Eligible students (electorate) -->
            <div class="bg-white rounded-[20px] p-5 py-4 flex items-center gap-4 shadow-sm flex-1 min-w-[180px]">
                <div class="bg-blue-600 w-[52px] h-[52px] rounded-[16px] flex items-center justify-center text-white shrink-0 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                    </svg>
                </div>
                <div>
                    <div id="report-stat-eligible" class="text-[28px] font-bold text-gray-900 leading-tight">{{ number_format($stats['eligible_students']) }}</div>
                    <div class="text-[11px] text-gray-500 font-semibold tracking-wide">Eligible students</div>
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
                    <div id="report-stat-votes-cast" class="text-[28px] font-bold text-gray-900 leading-tight">{{ number_format($stats['live_vote_cast']) }}</div>
                    <div class="text-[11px] text-gray-500 font-semibold tracking-wide">Votes cast</div>
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
                    <div id="report-stat-positions" class="text-[28px] font-bold text-gray-900 leading-tight">{{ number_format($stats['total_positions']) }}</div>
                    <div class="text-[11px] text-gray-500 font-semibold tracking-wide">Total positions</div>
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
                    <div id="report-stat-candidates" class="text-[28px] font-bold text-gray-900 leading-tight">{{ number_format($stats['running_candidates']) }}</div>
                    <div class="text-[11px] text-gray-500 font-semibold tracking-wide">Running candidates</div>
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
                    <div id="report-stat-turnout" class="text-[28px] font-bold text-gray-900 leading-tight">{{ number_format($stats['turnout_percent'], 1) }}%</div>
                    <div class="text-[11px] text-gray-500 font-semibold tracking-wide">Voter turnout</div>
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
                        <p class="text-[12px] text-gray-400 font-semibold mb-1">Total students</p>
                        <p id="report-rt-total-students" class="text-[30px] font-bold text-gray-900 leading-none">{{ number_format($totalStudents) }}</p>
                    </div>
                    <!-- Turnout -->
                    <div>
                        <p class="text-[12px] text-gray-400 font-semibold mb-1">Turnout</p>
                        <p id="report-rt-turnout-pct" class="text-[30px] font-bold text-[#0b64f9] leading-none">{{ number_format($turnoutPct, 1) }}%</p>
                    </div>
                    <!-- Voted -->
                    <div>
                        <p class="text-[12px] text-gray-400 font-semibold mb-1">Voted</p>
                        <p id="report-rt-voted" class="text-[30px] font-bold text-[#24b93b] leading-none">{{ number_format((int) ($rt['voted_count'] ?? 0)) }}</p>
                    </div>
                    <!-- Not Yet -->
                    <div>
                        <p class="text-[12px] text-gray-400 font-semibold mb-1">Not yet voted</p>
                        <p id="report-rt-not-yet" class="text-[30px] font-bold text-gray-400 leading-none">{{ number_format((int) ($rt['not_yet_voted'] ?? 0)) }}</p>
                    </div>
                </div>

                <!-- Visual donut-style indicator -->
                <div class="mt-6 pt-5 border-t border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[12px] text-gray-400 font-semibold">Overall progress</span>
                        <span id="report-rt-progress-ratio" class="text-[12px] font-bold text-gray-700">{{ number_format((int) ($rt['voted_count'] ?? 0)) }} / {{ number_format($totalStudents) }}</span>
                    </div>
                    <div class="report-progress-track w-full bg-gray-100 rounded-full h-[10px] overflow-hidden">
                        <div id="report-overall-fill" class="report-progress-fill bg-[#0b64f9] max-w-full w-[{{ number_format($barWidth, 4, '.', '') }}%]"></div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: PER YEAR LEVEL TURNOUT -->
            <div class="md:col-span-3 bg-white rounded-2xl p-6 shadow-sm">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-5">Per Year Level Turnout</p>

                <div id="report-year-level-rows" class="flex flex-col gap-5">
                    @forelse($data['per_year_level_turnout'] as $index => $row)
                        @php
                            $yp = (float) ($row['turnout_percent'] ?? 0);
                            $yw = min(100, max(0, $yp));
                            $color = $yearBarColors[$index % count($yearBarColors)];
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[13.5px] font-semibold text-gray-700">{{ $row['year_level'] }}</span>
                                <span class="text-[13px] font-bold text-gray-900">{{ number_format($yp, 1) }}%</span>
                            </div>
                            <div class="report-progress-track w-full bg-gray-100 rounded-full h-[10px] overflow-hidden">
                                <div class="report-year-fill report-progress-fill max-w-full bg-[{{ $color }}] w-[{{ number_format($yw, 4, '.', '') }}%]"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic">No year-level data yet.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <script>
        (function () {
            const url = document.body.dataset.reportLiveUrl;
            const pollMs = parseInt(document.body.dataset.reportPollMs || '5000', 10) || 5000;
            if (!url) {
                return;
            }

            const YEAR_COLORS = ['#0b64f9', '#24b93b', '#f59e0b', '#ef4444'];

            function fmtInt(n) {
                return new Intl.NumberFormat().format(Number(n) || 0);
            }

            function el(id) {
                return document.getElementById(id);
            }

            function applyReportData(data) {
                const s = data.stats_card_data || {};
                const rt = data.realtime_turnout || {};
                const election = data.election;

                const setText = (id, text) => {
                    const node = el(id);
                    if (node) {
                        node.textContent = text;
                    }
                };

                setText('report-stat-eligible', fmtInt(s.eligible_students ?? 0));
                setText('report-stat-votes-cast', fmtInt(s.live_vote_cast ?? 0));
                setText('report-stat-positions', fmtInt(s.total_positions ?? 0));
                setText('report-stat-candidates', fmtInt(s.running_candidates ?? 0));
                setText('report-stat-turnout', Number(s.turnout_percent ?? 0).toFixed(1) + '%');

                const totalStudents = Number(rt.total_students ?? 0);
                const turnoutPct = Number(rt.turnout_percent ?? 0);
                const votedCount = Number(rt.voted_count ?? 0);
                const notYet = Number(rt.not_yet_voted ?? 0);
                const barWidth = totalStudents > 0 ? Math.min(100, Math.max(0, turnoutPct)) : 0;

                setText('report-rt-total-students', fmtInt(totalStudents));
                setText('report-rt-turnout-pct', turnoutPct.toFixed(1) + '%');
                setText('report-rt-voted', fmtInt(votedCount));
                setText('report-rt-not-yet', fmtInt(notYet));
                setText('report-rt-progress-ratio', fmtInt(votedCount) + ' / ' + fmtInt(totalStudents));

                const overallFill = el('report-overall-fill');
                if (overallFill) {
                    overallFill.style.width = barWidth.toFixed(4) + '%';
                }

                const electionLine = el('report-election-line');
                const electionName = el('report-election-name');
                if (election && election.name) {
                    if (electionLine) {
                        electionLine.classList.remove('hidden');
                    }
                    if (electionName) {
                        electionName.textContent = election.name;
                    }
                } else {
                    if (electionLine) {
                        electionLine.classList.add('hidden');
                    }
                }

                const yearRows = data.per_year_level_turnout || [];
                const container = el('report-year-level-rows');
                if (container) {
                    if (yearRows.length === 0) {
                        container.innerHTML = '<p class="text-sm text-gray-500 italic">No year-level data yet.</p>';
                    } else {
                        container.innerHTML = yearRows.map(function (row, i) {
                            const yp = Number(row.turnout_percent ?? 0);
                            const yw = Math.min(100, Math.max(0, yp));
                            const color = YEAR_COLORS[i % YEAR_COLORS.length];
                            const label = String(row.year_level ?? '');
                            const div = document.createElement('div');
                            div.textContent = label;
                            const safeLabel = div.innerHTML;
                            return (
                                '<div>' +
                                '<div class="flex items-center justify-between mb-2">' +
                                '<span class="text-[13.5px] font-semibold text-gray-700">' + safeLabel + '</span>' +
                                '<span class="text-[13px] font-bold text-gray-900">' + yp.toFixed(1) + '%</span>' +
                                '</div>' +
                                '<div class="report-progress-track w-full bg-gray-100 rounded-full h-[10px] overflow-hidden">' +
                                '<div class="report-progress-fill max-w-full" style="width:' + yw.toFixed(4) + '%;background-color:' + color + '"></div>' +
                                '</div>' +
                                '</div>'
                            );
                        }).join('');
                    }
                }

                const liveUpdated = el('report-live-updated');
                if (liveUpdated) {
                    liveUpdated.textContent = 'Last updated ' + new Date().toLocaleTimeString();
                }
            }

            async function refresh() {
                try {
                    const res = await fetch(url, {
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    if (!res.ok) {
                        return;
                    }
                    applyReportData(await res.json());
                } catch (e) {
                    /* keep last good snapshot */
                }
            }

            setInterval(refresh, pollMs);
            document.addEventListener('visibilitychange', function () {
                if (document.visibilityState === 'visible') {
                    refresh();
                }
            });
            refresh();
        })();
    </script>

</body>
</html>
