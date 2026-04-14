@extends('components.sao-layout')

@section('title', 'SAO Dashboard')

@section('content')
    <h2 class="mb-6 text-[1.75rem] font-bold text-white">Dashboard</h2>

    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-12">
        <div class="rounded-lg border-4 border-[#38a9fa] bg-white p-7 text-black lg:col-span-8">
            <h5 class="mb-6 text-lg font-bold uppercase tracking-wider">Election Overview</h5>

            <div class="grid grid-cols-[150px_auto] gap-y-4">
                <div class="text-[1.05rem] font-bold text-gray-900">Election Name:</div>
                <div class="text-[1.05rem]">{{ $data['election']['name'] ?? 'No Active Election' }}</div>

                <div class="text-[1.05rem] font-bold text-gray-900">Election Status:</div>
                <div class="text-[1.05rem]">{{ $data['election']['status'] ?? 'Not Started' }}</div>

                <div class="text-[1.05rem] font-bold text-gray-900">Results Status:</div>
                @php
                    $resultsStatus = $data['results_status'] ?? 'Not Published';
                @endphp
                <div class="{{ $resultsStatus === 'Published' ? 'text-green-600' : 'text-[#dc3545]' }} text-[1.05rem]">
                    {{ $resultsStatus }}
                </div>
            </div>
        </div>

        <div class="flex flex-col justify-between rounded-lg bg-white p-7 text-black lg:col-span-4">
            <h5 class="mb-6 text-lg font-bold uppercase tracking-wider">Voter Turnout</h5>

            <div class="grid w-full grid-cols-2 gap-x-2 gap-y-5 text-[1rem]">
                <div>
                    <div class="mb-[0.2rem] text-gray-800">Total Voters:</div>
                    <div class="text-2xl font-bold leading-tight">{{ $data['realtime_turnout']['total_students'] ?? 0 }}</div>
                </div>
                <div>
                    <div class="mb-[0.2rem] text-gray-800">Turnout:</div>
                    <div class="text-2xl font-bold leading-tight">
                        {{ number_format((float) ($data['realtime_turnout']['turnout_percent'] ?? 0), 2) }}%
                    </div>
                </div>
                <div>
                    <div class="mb-[0.2rem] text-gray-800">Voted:</div>
                    <div class="text-2xl font-bold leading-tight">{{ $data['realtime_turnout']['voted_count'] ?? 0 }}</div>
                </div>
                <div>
                    <div class="mb-[0.2rem] text-gray-800">Not Yet:</div>
                    <div class="text-2xl font-bold leading-tight">{{ $data['realtime_turnout']['not_yet_voted'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-2 rounded-lg bg-white p-7 text-black">
        <h5 class="mb-8 text-lg font-bold uppercase tracking-wider">Per Year Level Turnout</h5>

        <div class="flex flex-col gap-5 px-1 pb-2">
            @forelse(($data['per_year_level_turnout'] ?? []) as $yearTurnout)
                @php
                    $percentage = max(0, min(100, (float) ($yearTurnout['turnout_percent'] ?? 0)));
                @endphp

                <div class="flex items-center gap-5">
                    <div class="w-32 text-base text-gray-900">{{ $yearTurnout['year_level'] ?? 'N/A' }}</div>

                    <progress
                        class="h-[12px] w-full flex-1 overflow-hidden rounded-full [&::-moz-progress-bar]:bg-[#111827] [&::-webkit-progress-bar]:bg-[#d9d9d9] [&::-webkit-progress-value]:bg-[#111827]"
                        value="{{ $percentage }}"
                        max="100"
                    ></progress>

                    <div class="w-12 text-right text-[1.05rem] tracking-wide text-gray-900">{{ number_format($percentage, 0) }}%</div>
                </div>
            @empty
                <div class="text-[1.05rem] text-gray-700">No year level turnout data available.</div>
            @endforelse
        </div>
    </div>
@endsection