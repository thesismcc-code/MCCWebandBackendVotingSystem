@extends('components.admin-layout')

@section('title', 'Admin Dashboard')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <!-- Total Registered Voters -->
        <div class="bg-white rounded-xl p-4 flex items-center shadow-lg">
            <div class="p-3 bg-blue-600 rounded-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                </svg>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900">
                    {{ $data['stats_card_data']['total_register_voters'] }}
                </div>
                <div class="text-[10px] text-gray-500 uppercase font-semibold text-nowrap">Total Registered Voters</div>
            </div>
        </div>

        <!-- Live Votes Cast -->
        <div class="bg-white rounded-xl p-4 flex items-center shadow-lg">
            <div class="p-3 bg-green-500 rounded-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900">
                    {{ $data['stats_card_data']['live_vote_cast'] }}
                </div>
                <div class="text-[10px] text-gray-500 uppercase font-semibold text-nowrap">Live Votes Cast</div>
            </div>
        </div>

        <!-- Running Candidates -->
        <div class="bg-white rounded-xl p-4 flex items-center shadow-lg">
            <div class="p-3 bg-yellow-500 rounded-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                </svg>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900">
                    {{ $data['stats_card_data']['running_candidates'] }}
                </div>
                <div class="text-[10px] text-gray-500 uppercase font-semibold text-nowrap">Running Candidates</div>
            </div>
        </div>

        <!-- Turnout Rates -->
        <div class="bg-white rounded-xl p-4 flex items-center shadow-lg">
            <div class="p-3 bg-red-500 rounded-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                </svg>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900">
                    {{ $data['stats_card_data']['turn_out_rates']['turnout_percent'] }}%
                </div>
                <div class="text-[10px] text-gray-500 uppercase font-semibold text-nowrap">Turnout Rates</div>
            </div>
        </div>
    </div>

    <!-- ... rest of your file (Results Section) -->

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-3 bg-white rounded-2xl p-6 shadow-xl">
             <h3 class="text-blue-900 font-bold text-md mb-6 uppercase">Live Candidates Result</h3>

             {{-- Example of how to loop through dynamic candidate results once you have them --}}
             @forelse($data['live_candidate_result'] as $candidate)
                @include('components.candidate-row', [
                    'name' => $candidate['name'],
                    'votes' => $candidate['votes'],
                    'percentage' => $candidate['percentage'],
                ])
             @empty
                <p class="text-gray-400 italic">No candidate results available yet.</p>
             @endforelse
        </div>
    </div>
@endsection
