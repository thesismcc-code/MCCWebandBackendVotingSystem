<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Control - Fingerprint Voting System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #102864;
        }

        .bg-main-panel {
            background-color: #0C3189;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body x-data="{ activeModal: null }" class="p-4 md:p-6 min-h-screen text-white flex flex-col font-sans relative">

    <!-- HEADER -->
    <div class="max-w-7xl mx-auto w-full mb-5 flex items-center gap-4 px-2">
        <a href="{{ route('view.quick-access') }}"
            class="bg-white text-[#113285] rounded-full w-10 h-10 flex items-center justify-center hover:scale-110 transition-transform shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white leading-tight">Election Control</h1>
            <p class="text-blue-200 text-[11px] font-medium">Manage the setup of the voting cycle.</p>
        </div>
    </div>

    <!-- SUCCESS / ERROR BANNERS -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto w-full mb-4 px-2">
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="max-w-7xl mx-auto w-full mb-4 px-2">
            <div class="bg-red-50 border border-red-200 text-red-600 text-sm font-medium px-4 py-3 rounded-xl">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- MAIN CONTAINER -->
    <div
        class="max-w-7xl mx-auto w-full bg-main-panel rounded-3xl p-6 md:p-10 relative shadow-2xl flex-1 border border-blue-800/30">

        {{-- Show which election is being edited --}}
        @if ($activeElection)
            <div
                class="mb-6 px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white/80 text-[13px] font-medium flex items-center gap-2">
                <svg class="w-4 h-4 text-green-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                Editing: <span class="text-white font-bold">{{ $activeElection->getElectionName() }}</span>
                <span
                    class="ml-2 text-[11px] px-2 py-0.5 rounded-full {{ $activeElection->isActive() ? 'bg-green-500' : 'bg-yellow-500' }} text-white font-bold uppercase">
                    {{ $activeElection->getStatus() }}
                </span>
            </div>
        @else
            <div
                class="mb-6 px-4 py-3 bg-yellow-500/20 border border-yellow-400/30 rounded-xl text-yellow-200 text-[13px] font-medium">
                ⚠ No active or upcoming election found. Please create one first.
            </div>
        @endif

        <!-- CARDS GRID -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- General Settings -->
            <div @click="activeModal = 'general'"
                class="bg-white rounded-2xl p-5 flex items-center justify-between shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all group cursor-pointer h-28">
                <div class="flex items-center gap-4">
                    <div
                        class="bg-[#0066FF] w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold text-lg">General Settings</h3>
                </div>
                <div class="text-[#113285] group-hover:translate-x-1 transition-transform">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <!-- Schedule Settings -->
            <div @click="activeModal = 'schedule'"
                class="bg-white rounded-2xl p-5 flex items-center justify-between shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all group cursor-pointer h-28">
                <div class="flex items-center gap-4">
                    <div
                        class="bg-[#00CC00] w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold text-lg">Schedule Settings</h3>
                </div>
                <div class="text-[#113285] group-hover:translate-x-1 transition-transform">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <!-- Position Setup -->
            <a href="{{ route('view.election-control-posistion-setup') }}"
                class="bg-white rounded-2xl p-5 flex items-center justify-between shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all group cursor-pointer h-28">
                <div class="flex items-center gap-4">
                    <div
                        class="bg-[#FCD34D] w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-[#0f172a] font-bold text-lg">Position Setup</h3>
                </div>
                <div class="text-[#113285] group-hover:translate-x-1 transition-transform">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </a>

        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════════════ -->
    <!-- MODALS                                                                 -->
    <!-- ═══════════════════════════════════════════════════════════════════════ -->
    <div x-show="activeModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4" role="dialog"
        aria-modal="true">

        <!-- Backdrop -->
        <div x-show="activeModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="activeModal = null">
        </div>

        <!-- ═══ GENERAL SETTINGS MODAL ═══ -->
        <div x-show="activeModal === 'general'" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 relative z-50">

            <h2 class="text-2xl font-bold text-gray-900 mb-6">General Settings</h2>

            @if (!$activeElection)
                <p class="text-red-500 text-sm mb-4 font-medium">No active or upcoming election found. Cannot save.</p>
            @endif

            <form action="{{ route('election.save-general') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Election Name -->
                <div class="grid grid-cols-12 gap-4 items-center">
                    <label class="col-span-4 text-gray-700 font-medium text-sm">Election Name:</label>
                    <div class="col-span-8">
                        <input type="text" name="election_name"
                            value="{{ old('election_name', $activeElection?->getElectionName() ?? '') }}"
                            placeholder="Election Name"
                            class="w-full border border-gray-300 rounded-md p-2.5 text-gray-800 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <!-- Semester -->
                <div class="grid grid-cols-12 gap-4 items-center">
                    <label class="col-span-4 text-gray-700 font-medium text-sm">Semester:</label>
                    <div class="col-span-8 relative">
                        <select name="semester"
                            class="w-full border border-gray-300 rounded-md p-2.5 text-gray-700 text-sm appearance-none focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <option value="">Select Semester</option>
                            @foreach (['1st Semester', '2nd Semester', 'Summer'] as $sem)
                                <option value="{{ $sem }}"
                                    {{ old('semester', $activeElection?->getSemester()) === $sem ? 'selected' : '' }}>
                                    {{ $sem }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Academic Year -->
                <div class="grid grid-cols-12 gap-4 items-center">
                    <label class="col-span-4 text-gray-700 font-medium text-sm">Academic Year:</label>
                    <div class="col-span-8 relative">
                        <select name="academic_year"
                            class="w-full border border-gray-300 rounded-md p-2.5 text-gray-700 text-sm appearance-none focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <option value="">Select Academic Year</option>
                            @foreach (['2024-2025', '2025-2026', '2026-2027', '2027-2028'] as $year)
                                <option value="{{ $year }}"
                                    {{ old('academic_year', $activeElection?->getAcademicYear()) === $year ? 'selected' : '' }}>
                                    {{ $year }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="activeModal = null"
                        class="px-6 py-2 rounded-md border border-red-500 text-red-500 text-sm font-medium hover:bg-red-50 transition-colors">Cancel</button>
                    <button type="submit" {{ !$activeElection ? 'disabled' : '' }}
                        class="px-8 py-2 rounded-md bg-[#22c504] text-white text-sm font-bold hover:bg-green-600 shadow-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed">Save</button>
                </div>
            </form>
        </div>

        <!-- ═══ SCHEDULE SETTINGS MODAL ═══ -->
        <div x-show="activeModal === 'schedule'" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 relative z-50">

            <h2 class="text-2xl font-bold text-gray-900 mb-6">Schedule Settings</h2>

            @if (!$activeElection)
                <p class="text-red-500 text-sm mb-4 font-medium">No active or upcoming election found. Cannot save.</p>
            @endif

            <form action="{{ route('election.save-schedule') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Election Date: From -->
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-4 flex justify-end pt-2">
                        <span class="text-gray-700 font-medium text-sm">Election Date:</span>
                    </div>
                    <div class="col-span-8 space-y-3">
                        <div class="grid grid-cols-12 gap-2 items-center">
                            <label class="col-span-2 text-gray-600 text-sm text-right pr-1">From:</label>
                            <div class="col-span-10">
                                <input type="date" name="start_date"
                                    value="{{ old('start_date', $activeElection?->getStartDate() ? \Carbon\Carbon::parse($activeElection->getStartDate())->format('Y-m-d') : '') }}"
                                    class="w-full border border-gray-300 rounded-md p-2.5 text-gray-700 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-2 items-center">
                            <label class="col-span-2 text-gray-600 text-sm text-right pr-1">To:</label>
                            <div class="col-span-10">
                                <input type="date" name="end_date"
                                    value="{{ old('end_date', $activeElection?->getEndDate() ? \Carbon\Carbon::parse($activeElection->getEndDate())->format('Y-m-d') : '') }}"
                                    class="w-full border border-gray-300 rounded-md p-2.5 text-gray-700 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Opening Time -->
                <div class="grid grid-cols-12 gap-4 items-center">
                    <label class="col-span-4 text-gray-700 font-medium text-sm text-right">Opening Time:</label>
                    <div class="col-span-8">
                        <input type="time" name="opening_time"
                            value="{{ old('opening_time', $activeElection?->getOpeningTime() ?? '') }}"
                            class="w-full border border-gray-300 rounded-md p-2.5 text-gray-700 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                    </div>
                </div>

                <!-- Closing Time -->
                <div class="grid grid-cols-12 gap-4 items-center">
                    <label class="col-span-4 text-gray-700 font-medium text-sm text-right">Closing Time:</label>
                    <div class="col-span-8">
                        <input type="time" name="closing_time"
                            value="{{ old('closing_time', $activeElection?->getClosingTime() ?? '') }}"
                            class="w-full border border-gray-300 rounded-md p-2.5 text-gray-700 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="activeModal = null"
                        class="px-6 py-2 rounded-md border border-red-500 text-red-500 text-sm font-medium hover:bg-red-50 transition-colors">Cancel</button>
                    <button type="submit" {{ !$activeElection ? 'disabled' : '' }}
                        class="px-8 py-2 rounded-md bg-[#22c504] text-white text-sm font-bold hover:bg-green-600 shadow-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed">Save</button>
                </div>
            </form>
        </div>

    </div>

</body>

</html>
