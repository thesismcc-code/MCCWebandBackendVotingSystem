@extends('components.comelec-layout')

@section('title', 'COMELEC Dashboard')

@section('content')
    <h2 class="mb-6 text-[1.75rem] font-bold text-white">Dashboard</h2>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div class="rounded-xl border-[3px] border-[#3b82f6] bg-white p-6 text-black shadow-md">
            <h5 class="mb-6 text-sm font-extrabold uppercase tracking-wide text-[#1a4bbc]">
                Real Time Voter Turnout
            </h5>

            <div class="grid grid-cols-2 gap-x-4 gap-y-6">
                <div>
                    <div class="mb-1 text-sm font-medium text-gray-600">Total Voters:</div>
                    <div class="text-2xl font-extrabold text-black">
                        {{ $data['realtime_turnout']['total_students'] ?? 0 }}</div>
                </div>
                <div>
                    <div class="mb-1 text-sm font-medium text-gray-600">Turnout:</div>
                    <div class="text-2xl font-extrabold text-black">
                        {{ number_format((float) ($data['realtime_turnout']['turnout_percent'] ?? 0), 2) }}%
                    </div>
                </div>
                <div>
                    <div class="mb-1 text-sm font-medium text-gray-600">Voted:</div>
                    <div class="text-2xl font-extrabold text-black">
                        {{ $data['realtime_turnout']['voted_count'] ?? 0 }}</div>
                </div>
                <div>
                    <div class="mb-1 text-sm font-medium text-gray-600">Not Yet:</div>
                    <div
                        class="text-2xl font-extrabold text-black underline decoration-[#1a4bbc] decoration-[3px] underline-offset-4">
                        {{ $data['realtime_turnout']['not_yet_voted'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-xl bg-white p-6 text-black shadow-md">
            <h5 class="mb-6 text-sm font-extrabold uppercase tracking-wide text-[#1a4bbc]">
                Per Year Level Turnout
            </h5>

            <div class="flex flex-col gap-4">
                @forelse(($data['per_year_level_turnout'] ?? []) as $yearTurnout)
                    @php
                        $percentage = max(0, min(100, (float) ($yearTurnout['turnout_percent'] ?? 0)));
                    @endphp

                    <div class="flex items-center gap-3">
                        <div class="w-20 shrink-0 font-medium text-gray-800">{{ $yearTurnout['year_level'] ?? 'N/A' }}</div>
                        <div class="min-w-0 flex-1">
                            <progress
                                class="h-4 w-full overflow-hidden rounded-full [&::-moz-progress-bar]:bg-gray-900 [&::-webkit-progress-bar]:bg-gray-200 [&::-webkit-progress-value]:bg-gray-900"
                                value="{{ $percentage }}"
                                max="100"
                            ></progress>
                        </div>
                        <div class="w-12 shrink-0 text-right text-sm font-bold text-gray-700">
                            {{ number_format($percentage, 0) }}%
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-700">No year level turnout data available.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
