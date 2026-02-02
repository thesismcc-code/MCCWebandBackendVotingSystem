@extends('components.admin-layout')

@section('title', 'Admin Dashboard')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-4 flex items-center shadow-lg">
            <div class="p-3 bg-blue-600 rounded-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                </svg>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900">0</div>
                <div class="text-[10px] text-gray-500 uppercase font-semibold text-nowrap">Total Registered Voters</div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 flex items-center shadow-lg">
            <div class="p-3 bg-green-500 rounded-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900">0</div>
                <div class="text-[10px] text-gray-500 uppercase font-semibold text-nowrap">Live Votes Cast</div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 flex items-center shadow-lg">
            <div class="p-3 bg-yellow-500 rounded-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z">
                    </path>
                </svg>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900">0</div>
                <div class="text-[10px] text-gray-500 uppercase font-semibold text-nowrap">Running Candidates</div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 flex items-center shadow-lg">
            <div class="p-3 bg-red-500 rounded-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                </svg>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900">0%</div>
                <div class="text-[10px] text-gray-500 uppercase font-semibold text-nowrap">Turnout Rates</div>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <!-- Candidates Result Column -->
        <div class="lg:col-span-3 bg-white rounded-2xl p-6 text-gray-800 shadow-xl overflow-hidden">
            <h3 class="text-blue-900 font-bold text-md mb-6 uppercase">Live Candidates Result</h3>

            <div class="space-y-8">
                <div>
                    <div
                        class="flex justify-between text-[10px] font-bold text-gray-900 uppercase border-b border-gray-100 pb-2 mb-4">
                        <span class="w-1/3">President</span>
                        <span class="w-1/4 text-center">Votes</span>
                        <span class="w-1/3">Turnout</span>
                    </div>
                    <div class="space-y-3">
                        @include('components.candidate-row', [
                            'name' => 'Honey Malang',
                            'votes' => 53,
                            'percentage' => 23,
                        ])
                        @include('components.candidate-row', [
                            'name' => 'Myles Macrohon',
                            'votes' => 33,
                            'percentage' => 15,
                        ])
                        @include('components.candidate-row', [
                            'name' => 'Myles Macrohon',
                            'votes' => 33,
                            'percentage' => 15,
                        ])
                        @include('components.candidate-row', [
                            'name' => 'Myles Macrohon',
                            'votes' => 33,
                            'percentage' => 15,
                        ])
                        @include('components.candidate-row', [
                            'name' => 'Myles Macrohon',
                            'votes' => 33,
                            'percentage' => 15,
                        ])
                    </div>
                </div>
            </div>
        </div>

        <!-- Senators Column -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 text-gray-800 shadow-xl overflow-hidden">
            <h3 class="text-[10px] font-bold text-gray-900 uppercase mb-4">Senators</h3>
            <div class="space-y-1 h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                @include('components.candidate-row', [
                    'name' => 'Honey Malang',
                    'votes' => 53,
                    'percentage' => 23,
                    'mini' => true,
                ])
                @include('components.candidate-row', [
                    'name' => 'Jose Perolino',
                    'votes' => 33,
                    'percentage' => 15,
                    'mini' => true,
                ])
                @include('components.candidate-row', [
                    'name' => 'Jose Perolino',
                    'votes' => 33,
                    'percentage' => 15,
                    'mini' => true,
                ])
                @include('components.candidate-row', [
                    'name' => 'Jose Perolino',
                    'votes' => 33,
                    'percentage' => 15,
                    'mini' => true,
                ])
                @include('components.candidate-row', [
                    'name' => 'Jose Perolino',
                    'votes' => 33,
                    'percentage' => 15,
                    'mini' => true,
                ])
                @include('components.candidate-row', [
                    'name' => 'Jose Perolino',
                    'votes' => 33,
                    'percentage' => 15,
                    'mini' => true,
                ])
                <!-- Repeat as needed -->
            </div>
        </div>
    </div>
@endsection
