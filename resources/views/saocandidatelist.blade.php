<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidates List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #102864;
        }

        .bg-main-panel {
            background-color: #0C3189;
        }
    </style>
</head>

<body class="p-4 md:p-6 min-h-screen text-white flex flex-col">
    @php
        $positionColumns = array_chunk($groupedCandidates, (int) ceil(max(count($groupedCandidates), 1) / 2));
        $electionTitle = $activeElection?->getElectionName() ?: 'No Active Election';
    @endphp

    <div class="max-w-7xl mx-auto w-full mb-5 flex items-center justify-between px-2 mt-4 md:mt-2">
        <div class="flex items-center gap-4">
            <a href="{{ route('view.sao-dashboard') }}"
                class="bg-white text-[#113285] rounded-full w-10 h-10 flex items-center justify-center hover:scale-110 transition-transform shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white leading-tight">Candidates List</h1>
                <p class="text-blue-200 text-[11px] font-medium mt-0.5">{{ $electionTitle }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto w-full bg-main-panel rounded-3xl p-6 md:p-10 shadow-2xl flex-1 flex flex-col mb-4">
        <div class="bg-white rounded-2xl overflow-hidden shadow-xl w-full max-w-[1080px] mx-auto">
            @if (count($groupedCandidates) === 0)
                <div class="p-8 md:p-10 text-center text-gray-700">
                    <h2 class="text-lg md:text-xl font-semibold">No candidates found for the active election.</h2>
                    <p class="text-sm mt-2">Candidates will appear here once records are available in Firebase.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2">
                    @foreach ($positionColumns as $columnIndex => $positionColumn)
                        <div class="p-5 md:p-7 space-y-6 {{ $columnIndex === 0 && count($positionColumns) > 1 ? 'border-b md:border-b-0 md:border-r border-[#7D92AD]/40' : '' }}">
                            @foreach ($positionColumn as $position)
                                <section>
                                    <span
                                        class="inline-block bg-[#CADDFE] text-black text-[13px] md:text-[14px] font-bold tracking-wide uppercase px-4 py-1.5 rounded-md">{{ $position['position_name'] }}</span>
                                    <div class="mt-2 border border-[#aeb2b5] rounded-lg overflow-hidden shadow-sm">
                                        <div class="px-4 py-2 text-right text-[12px] text-gray-700 font-semibold border-b border-[#aeb2b5]">
                                            {{ count($position['candidates']) }} {{ count($position['candidates']) === 1 ? 'Candidate' : 'Candidates' }}
                                        </div>
                                        @foreach ($position['candidates'] as $candidateName)
                                            <div class="px-5 py-3.5 text-[15px] text-gray-900 {{ !$loop->last ? 'border-b border-[#aeb2b5]' : '' }}">
                                                {{ $candidateName }}
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</body>

</html>
