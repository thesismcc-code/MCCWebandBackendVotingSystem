@extends('components.comelec-layout')

@section('title', 'Manage Candidates')

@push('scripts')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
    <h2 class="mb-4 text-[1.75rem] font-bold text-white">Manage Candidates</h2>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-400/40 bg-green-900/30 px-4 py-3 text-sm text-green-100">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-lg border border-red-400/40 bg-red-900/30 px-4 py-3 text-sm text-red-100">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-amber-400/40 bg-amber-900/20 px-4 py-3 text-sm text-amber-100">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (! $activeElection)
        <div class="mb-6 rounded-lg border border-amber-400/50 bg-amber-900/20 px-4 py-3 text-sm text-amber-100">
            There is no active election. Candidate changes may be limited until an election is active.
        </div>
    @endif

    <div class="relative"
        x-data="{ activeModal: null, showAddCandidate: false, addPositionName: '', editingId: null }">

        <div class="rounded-[2rem] bg-[#0b2e7a] p-6 shadow-2xl md:p-8"
            :class="(activeModal || showAddCandidate) ? 'opacity-40 pointer-events-none' : ''">

            <h3 class="mb-8 text-lg font-bold uppercase tracking-wide text-white">Positions</h3>

            @if (count($positionRows) === 0)
                <p class="text-sm text-blue-100/90">No positions are configured yet. Ask an administrator to add
                    positions under Election Control.</p>
            @else
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($positionRows as $row)
                        <button type="button" @click="activeModal = '{{ $row['position_id'] }}'"
                            class="group relative flex h-32 cursor-pointer flex-col justify-between rounded-xl bg-white p-6 text-left shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                            <div>
                                <h3 class="text-[0.95rem] font-extrabold uppercase tracking-wide text-black">
                                    {{ $row['position_name'] }}</h3>
                                <p class="mt-1 text-lg font-bold text-black">{{ $row['candidate_count'] }}</p>
                                <p class="text-xs text-gray-500">Max {{ $row['max_votes'] }} &middot;
                                    {{ $row['remaining_slots'] }} slot(s) left</p>
                            </div>
                            <div
                                class="absolute bottom-5 right-5 flex h-8 w-8 items-center justify-center rounded-full bg-[#103080] transition-colors group-hover:bg-[#0c2466]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </div>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        @foreach ($positionRows as $row)
            <div x-show="activeModal === '{{ $row['position_id'] }}'" x-transition.opacity.duration.300ms
                x-cloak
                class="pointer-events-none fixed inset-0 z-[100] flex items-start justify-center overflow-y-auto bg-black/40 pt-12 md:pt-20">

                <div
                    class="pointer-events-auto relative mx-4 my-6 w-full max-w-[min(92vw,72rem)] rounded-xl border border-gray-100 bg-white text-black shadow-2xl">

                    <div class="flex items-center justify-between border-b border-gray-200 bg-white px-10 py-6">
                        <h3 class="text-2xl font-bold uppercase tracking-tight text-black">
                            {{ $row['position_name'] }}</h3>
                        <button type="button" @click="activeModal = null; editingId = null"
                            class="text-gray-400 transition-colors hover:text-gray-600">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="bg-white">
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto text-left text-base">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="min-w-[12rem] py-5 pl-10 text-base font-bold text-gray-900">Name</th>
                                        <th class="min-w-[10rem] px-4 py-5 text-base font-bold text-gray-900">Course</th>
                                        <th class="min-w-[7rem] px-3 py-5 text-base font-bold text-gray-900">Year Level</th>
                                        <th class="min-w-[8rem] pr-4 py-5 text-right text-base font-bold text-gray-900">Party
                                        </th>
                                        <th class="w-40 py-5 pr-10 text-right text-base font-bold text-gray-900">Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($row['candidates'] as $candidate)
                                        <tr class="border-b border-gray-100" x-show="editingId !== '{{ $candidate->getId() }}'" x-cloak>
                                            <td class="py-5 pl-10 font-medium text-gray-800">
                                                {{ $candidate->getFullName() }}</td>
                                            <td class="px-4 py-5 text-gray-700">{{ $candidate->getCourse() }}</td>
                                            <td class="px-3 py-5 text-gray-700">{{ $candidate->getYear() }}</td>
                                            <td class="pr-4 py-5 text-gray-700">{{ $candidate->getPoliticalParty() ?: '—' }}
                                            </td>
                                            <td class="py-5 pr-10 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                <button type="button"
                                                    @click="editingId = '{{ $candidate->getId() }}'"
                                                    class="flex h-10 w-10 items-center justify-center rounded bg-blue-500 text-white shadow-sm transition hover:bg-blue-600">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                                <form method="POST"
                                                    action="{{ route('comelec.candidate.delete') }}"
                                                    class="inline"
                                                    onsubmit="return confirm('Remove this candidate?');">
                                                    @csrf
                                                    <input type="hidden" name="candidate_id"
                                                        value="{{ $candidate->getId() }}">
                                                    <button type="submit"
                                                        class="flex h-10 w-10 items-center justify-center rounded bg-red-100 text-red-500 shadow-sm transition hover:bg-red-200">
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="border-b border-gray-200 bg-gray-50" x-show="editingId === '{{ $candidate->getId() }}'"
                                            x-cloak>
                                            <td colspan="5" class="px-10 py-6">
                                                <form method="POST"
                                                    action="{{ route('comelec.candidate.update') }}"
                                                    class="space-y-4">
                                                    @csrf
                                                    <input type="hidden" name="candidate_id"
                                                        value="{{ $candidate->getId() }}">
                                                    <input type="hidden" name="position_name"
                                                        value="{{ $row['position_name'] }}">

                                                    <div class="grid gap-4 text-base md:grid-cols-2">
                                                        <div>
                                                            <label class="mb-1 block font-medium text-gray-800">Full
                                                                name</label>
                                                            <input type="text" name="full_name" required
                                                                value="{{ old('candidate_id') === $candidate->getId() ? old('full_name', $candidate->getFullName()) : $candidate->getFullName() }}"
                                                                class="w-full rounded border border-gray-300 px-3 py-2.5 text-black focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                                        </div>
                                                        <div>
                                                            <label class="mb-1 block font-medium text-gray-800">Course</label>
                                                            <select name="course" required
                                                                class="w-full rounded border border-gray-300 px-3 py-2.5 text-black focus:border-blue-500 focus:outline-none">
                                                                @php
                                                                    $c =
                                                                        old('candidate_id') === $candidate->getId()
                                                                            ? old('course', $candidate->getCourse())
                                                                            : $candidate->getCourse();
                                                                @endphp
                                                                <option value="BSIT" @selected($c === 'BSIT')>BSIT</option>
                                                                <option value="BSCS" @selected($c === 'BSCS')>BSCS</option>
                                                                <option value="BSIS" @selected($c === 'BSIS')>BSIS</option>
                                                                <option value="BSCE" @selected($c === 'BSCE')>BSCE</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="mb-1 block font-medium text-gray-800">Year</label>
                                                            <select name="year" required
                                                                class="w-full rounded border border-gray-300 px-3 py-2.5 text-black focus:border-blue-500 focus:outline-none">
                                                                @php
                                                                    $y =
                                                                        old('candidate_id') === $candidate->getId()
                                                                            ? old('year', $candidate->getYear())
                                                                            : $candidate->getYear();
                                                                @endphp
                                                                <option value="1st Year" @selected($y === '1st Year')>1st
                                                                    Year</option>
                                                                <option value="2nd Year" @selected($y === '2nd Year')>2nd
                                                                    Year</option>
                                                                <option value="3rd Year" @selected($y === '3rd Year')>3rd
                                                                    Year</option>
                                                                <option value="4th Year" @selected($y === '4th Year')>4th
                                                                    Year</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="mb-1 block font-medium text-gray-800">Political party</label>
                                                            <input type="text" name="political_party"
                                                                value="{{ old('candidate_id') === $candidate->getId() ? old('political_party', $candidate->getPoliticalParty()) : $candidate->getPoliticalParty() }}"
                                                                class="w-full rounded border border-gray-300 px-3 py-2.5 text-black focus:border-blue-500 focus:outline-none">
                                                        </div>
                                                        <div class="md:col-span-2">
                                                            <label class="mb-1 block font-medium text-gray-800">Platform / Agenda</label>
                                                            <textarea name="platform_agenda" rows="3"
                                                                class="w-full rounded border border-gray-300 px-3 py-2.5 text-black focus:border-blue-500 focus:outline-none">{{ old('candidate_id') === $candidate->getId() ? old('platform_agenda', $candidate->getPlatformAgenda()) : $candidate->getPlatformAgenda() }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="flex gap-3 pt-2">
                                                        <button type="submit"
                                                            class="rounded bg-[#0061ff] px-5 py-2.5 text-base font-medium text-white hover:bg-blue-600">Save</button>
                                                        <button type="button" @click="editingId = null"
                                                            class="rounded border border-gray-300 px-5 py-2.5 text-base font-medium text-gray-700 hover:bg-gray-100">Cancel</button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-10 py-8 text-center text-base text-gray-600">No candidates
                                                for this position yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="border-t border-gray-200 bg-white px-10 py-6">
                            @if ($row['is_full'])
                                <div class="flex items-center gap-3 text-base font-medium text-gray-800">
                                    <svg class="h-5 w-5 shrink-0 text-yellow-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <span>Maximum candidates reached for this position.</span>
                                </div>
                            @else
                                <div class="flex flex-col items-stretch justify-between gap-4 sm:flex-row sm:items-center">
                                    <span class="text-base font-medium text-gray-800">You may add up to
                                        {{ $row['remaining_slots'] }} more candidate(s).</span>
                                    @if ($activeElection)
                                        <button type="button"
                                            @click='showAddCandidate = true; addPositionName = @json($row['position_name']); activeModal = null'
                                            class="inline-flex items-center justify-center gap-2 self-start rounded bg-[#0061ff] px-5 py-2.5 text-sm font-semibold tracking-wide text-white transition-colors hover:bg-blue-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                            </svg>
                                            Add Candidate
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div x-show="showAddCandidate" x-transition.opacity.duration.300ms x-cloak
            class="pointer-events-none fixed inset-0 z-[110] flex items-start justify-center overflow-y-auto bg-black/40 pt-8 md:pt-16">

            <div
                class="pointer-events-auto relative mx-4 my-8 w-full max-w-2xl rounded-xl border border-gray-100 bg-white text-black shadow-2xl">
                <div class="border-b border-gray-100 px-8 pb-4 pt-7">
                    <h3 class="text-2xl font-bold tracking-tight text-black">Add Candidates</h3>
                </div>

                <form method="POST" action="{{ route('comelec.candidate.save') }}" class="space-y-5 px-8 pb-8">
                    @csrf
                    <input type="hidden" name="position_name" :value="addPositionName">

                    <div class="space-y-4 text-base">
                        <div class="grid grid-cols-3 items-center gap-3">
                            <label class="col-span-1 font-medium text-black">Full Name:</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required
                                class="col-span-2 w-full rounded border border-gray-300 px-3 py-2.5 text-black focus:border-blue-500 focus:outline-none">
                        </div>
                        <div class="grid grid-cols-3 items-center gap-3">
                            <label class="col-span-1 font-medium text-black">Course:</label>
                            <select name="course" required
                                class="col-span-2 w-full rounded border border-gray-300 bg-white px-3 py-2.5 text-black focus:border-blue-500 focus:outline-none">
                                <option value="" disabled {{ old('course') ? '' : 'selected' }}>Select course</option>
                                <option value="BSIT" @selected(old('course') === 'BSIT')>BSIT</option>
                                <option value="BSCS" @selected(old('course') === 'BSCS')>BSCS</option>
                                <option value="BSIS" @selected(old('course') === 'BSIS')>BSIS</option>
                                <option value="BSCE" @selected(old('course') === 'BSCE')>BSCE</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-3 items-center gap-3">
                            <label class="col-span-1 font-medium text-black">Year:</label>
                            <select name="year" required
                                class="col-span-2 w-full rounded border border-gray-300 bg-white px-3 py-2.5 text-black focus:border-blue-500 focus:outline-none">
                                <option value="" disabled {{ old('year') ? '' : 'selected' }}>Select year</option>
                                <option value="1st Year" @selected(old('year') === '1st Year')>1st Year</option>
                                <option value="2nd Year" @selected(old('year') === '2nd Year')>2nd Year</option>
                                <option value="3rd Year" @selected(old('year') === '3rd Year')>3rd Year</option>
                                <option value="4th Year" @selected(old('year') === '4th Year')>4th Year</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-3 items-center gap-3">
                            <label class="col-span-1 font-medium text-black">Political Party:</label>
                            <input type="text" name="political_party" value="{{ old('political_party') }}"
                                class="col-span-2 w-full rounded border border-gray-300 px-3 py-2.5 text-black focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-base font-medium text-black">Platform / Agenda</label>
                        <textarea name="platform_agenda" rows="4"
                            class="w-full resize-none rounded border border-gray-300 px-3 py-2.5 text-base text-black focus:border-blue-500 focus:outline-none">{{ old('platform_agenda') }}</textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="button" @click="showAddCandidate = false"
                            class="w-full rounded border border-red-300 py-3 text-base font-medium text-red-500 transition-colors hover:bg-red-50">
                            Cancel
                        </button>
                        <button type="submit"
                            class="w-full rounded bg-[#0061ff] py-3 text-base font-medium text-white transition-colors hover:bg-blue-600">
                            Add Candidate
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
