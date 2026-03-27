@extends('components.admin-layout')
@section('title', 'Admin Dashboard')
@section('content')

    <h2 class="text-2xl font-bold text-white mb-6">Dashboard</h2>

    {{-- ── Stats Cards ─────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        @include('components.dashboard.statcard', [
            'value' => $data['stats_card_data']['total_register_voters'],
            'label' => 'Total Registered Voters',
            'color' => 'blue',
            'icon'  => 'user',
        ])

        @include('components.dashboard.statcard', [
            'value' => $data['stats_card_data']['live_vote_cast'],
            'label' => 'Live Votes Cast',
            'color' => 'green',
            'icon'  => 'check-circle',
        ])

        @include('components.dashboard.statcard', [
            'value' => $data['stats_card_data']['running_candidates'],
            'label' => 'Running Candidates',
            'color' => 'yellow',
            'icon'  => 'users',
        ])

        @include('components.dashboard.statcard', [
            'value' => $data['stats_card_data']['turn_out_rates']['turnout_percent'] . '%',
            'label' => 'Turnout Rates',
            'color' => 'red',
            'icon'  => 'percent',
        ])

    </div>

    {{-- ── Section Header ──────────────────────────────────────────────────── --}}
    <div class="flex items-center gap-2 mb-5">
        <span class="w-3 h-3 rounded-full bg-red-500 animate-pulse inline-block"></span>
        <h3 class="text-white font-extrabold text-sm tracking-widest uppercase">Live Candidate Results</h3>
    </div>

    {{-- ── Main Grid: Results + Sidebar ────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Left: Candidate Results by Position --}}
        <div class="lg:col-span-3 flex flex-col gap-5">

            @forelse($data['live_candidate_result'] as $position => $candidates)
                @include('components.dashboard.candidateposistioncard', [
                    'position'   => $position,
                    'candidates' => $candidates,
                ])
            @empty
                <div class="bg-white/10 rounded-2xl p-8 text-center text-white/50 italic">
                    No candidate results available yet.
                </div>
            @endforelse

        </div>

        {{-- Right Sidebar --}}
        <div class="lg:col-span-2 flex flex-col gap-5">

            @include('components.dashboard.realtimeturnoutcard', [
                'turnout' => $data['realtime_turnout'],
            ])

            @include('components.dashboard.yearlevelturnoutcard', [
                'yearLevels' => $data['per_year_level_turnout'],
            ])

        </div>

    </div>

@endsection
