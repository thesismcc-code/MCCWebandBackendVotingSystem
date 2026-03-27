{{--
    Candidate Position Card Component
    Props:
        $position   — string e.g. 'President'
        $candidates — array of candidate arrays:
                      [ candidate_id, name, party_list_id, votes, percentage ]
--}}

<div class="bg-white rounded-2xl shadow-lg overflow-hidden">

    {{-- Position Header --}}
    <div class="grid grid-cols-[1fr_auto_auto] items-center gap-4
                px-5 py-3 border-b border-gray-200 bg-gray-50">
        <span class="font-extrabold text-sm text-gray-900 uppercase tracking-wider">
            {{ $position }}
        </span>
        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider w-16 text-center">
            Votes
        </span>
        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider w-40 text-center">
            Turnout
        </span>
    </div>

    {{-- Candidate Rows --}}
    <div class="divide-y divide-gray-100">
        @foreach($candidates as $candidate)
            @include('components.dashboard.candidaterow', ['candidate' => $candidate])
        @endforeach
    </div>

</div>
